<?php
require '../lastfm.php';
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../artistas/index.php");
    exit;
}

$artista_id = $_POST['artista_id'];
$nombre_artista = $_POST['nombre_artista'];

// Verificamos que se seleccionó al menos un álbum
if (!isset($_POST['albumes']) || count($_POST['albumes']) === 0) {
    header("Location: importar_albumes.php?id=" . $artista_id . "&nombre=" . urlencode($nombre_artista));
    exit;
}

$albumes_seleccionados = $_POST['albumes'];
$importados = 0;

foreach ($albumes_seleccionados as $titulo_album) {

    // Obtenemos info detallada del álbum desde la API
    $info_album = lastfm_canciones_album($nombre_artista, $titulo_album);

    // Intentamos extraer el año
    $anio = null;
    if ($info_album && isset($info_album['wiki']['published'])) {
        // El formato es algo como "01 January 2000, 00:00"
        $fecha = $info_album['wiki']['published'];
        if (preg_match('/(\d{4})/', $fecha, $matches)) {
            $anio = (int) $matches[1];
        }
    }

    // Intentamos descargar la portada
    $nombre_imagen = null;
    if ($info_album && isset($info_album['image'])) {
        $imagen_url = '';
        foreach ($info_album['image'] as $img) {
            if ($img['size'] === 'extralarge' && !empty($img['#text'])) {
                $imagen_url = $img['#text'];
            }
        }

        if (!empty($imagen_url)) {
            $extension = 'jpg';
            $nombre_imagen = 'album_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            $destino = '../uploads/' . $nombre_imagen;

            // Descargamos la imagen
            $imagen_data = file_get_contents($imagen_url);
            if ($imagen_data !== false) {
                file_put_contents($destino, $imagen_data);
            } else {
                $nombre_imagen = null;
            }
        }
    }

    // Guardamos el álbum en la base de datos
    $sql = "INSERT INTO albumes (titulo, anio, artista_id, imagen) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "siis", $titulo_album, $anio, $artista_id, $nombre_imagen);

    if (mysqli_stmt_execute($stmt)) {
        $album_id = mysqli_insert_id($conexion);
        $importados++;

        // Si la API devolvió canciones, las importamos también
        if ($info_album && isset($info_album['tracks']['track'])) {
            $tracks = $info_album['tracks']['track'];

            // Si solo hay una canción, Last.fm no lo mete en un arreglo
            if (isset($tracks['name'])) {
                $tracks = [$tracks];
            }

            foreach ($tracks as $track) {
                $titulo_cancion = $track['name'];
                $duracion_seg = isset($track['duration']) ? (int) $track['duration'] : 0;

                // Convertimos segundos a formato mm:ss
                $duracion = null;
                if ($duracion_seg > 0) {
                    $min = floor($duracion_seg / 60);
                    $seg = $duracion_seg % 60;
                    $duracion = $min . ':' . str_pad($seg, 2, '0', STR_PAD_LEFT);
                }

                $sql_cancion = "INSERT INTO canciones (titulo, duracion, album_id) VALUES (?, ?, ?)";
                $stmt_cancion = mysqli_prepare($conexion, $sql_cancion);
                mysqli_stmt_bind_param($stmt_cancion, "ssi", $titulo_cancion, $duracion, $album_id);
                mysqli_stmt_execute($stmt_cancion);
                mysqli_stmt_close($stmt_cancion);
            }
        }
    }

    mysqli_stmt_close($stmt);
}

header("Location: ../artistas/detalle.php?id=" . $artista_id . "&mensaje=albumes_importados");
exit;
?>