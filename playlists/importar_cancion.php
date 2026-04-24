<?php
require '../lastfm.php';
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$playlist_id = $_POST['playlist_id'];
$nombre_cancion = $_POST['cancion'];
$nombre_artista = $_POST['artista'];
$busqueda = $_POST['busqueda'];

// URL de retorno
$url_retorno = "buscar_cancion.php?playlist_id=" . $playlist_id . "&q=" . urlencode($busqueda);

// ============================================================
// PASO 1: Verificar o crear el ARTISTA
// ============================================================
$sql = "SELECT id FROM artistas WHERE LOWER(nombre) = LOWER(?)";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "s", $nombre_artista);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$artista = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if ($artista) {
    $artista_id = $artista['id'];
} else {
    // Intentamos obtener el género desde Last.fm
    $info_artista = lastfm_info_artista($nombre_artista);
    $genero = '';
    if ($info_artista && isset($info_artista['tags']['tag']) && count($info_artista['tags']['tag']) > 0) {
        $genero = $info_artista['tags']['tag'][0]['name'];
    }

    $sql = "INSERT INTO artistas (nombre, genero) VALUES (?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $nombre_artista, $genero);
    mysqli_stmt_execute($stmt);
    $artista_id = mysqli_insert_id($conexion);
    mysqli_stmt_close($stmt);
}

// ============================================================
// PASO 2: Obtener info de la canción desde Last.fm
// ============================================================
$info_track = lastfm_info_cancion($nombre_artista, $nombre_cancion);

// Extraemos el nombre del álbum
$nombre_album = 'Singles';  // Valor por defecto si no hay álbum
if ($info_track && isset($info_track['album']['title'])) {
    $nombre_album = $info_track['album']['title'];
}

// Extraemos la duración
$duracion = null;
if ($info_track && isset($info_track['duration'])) {
    $duracion_ms = (int) $info_track['duration'];
    if ($duracion_ms > 0) {
        $duracion_seg = floor($duracion_ms / 1000);
        $min = floor($duracion_seg / 60);
        $seg = $duracion_seg % 60;
        $duracion = $min . ':' . str_pad($seg, 2, '0', STR_PAD_LEFT);
    }
}

// ============================================================
// PASO 3: Verificar o crear el ÁLBUM
// ============================================================
$sql = "SELECT id FROM albumes WHERE LOWER(titulo) = LOWER(?) AND artista_id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "si", $nombre_album, $artista_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$album = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if ($album) {
    $album_id = $album['id'];
} else {
    // Intentamos descargar la portada del álbum
    $nombre_imagen = null;
    if ($info_track && isset($info_track['album']['image'])) {
        $imagen_url = '';
        foreach ($info_track['album']['image'] as $img) {
            if ($img['size'] === 'extralarge' && !empty($img['#text'])) {
                $imagen_url = $img['#text'];
            }
        }
        if (!empty($imagen_url)) {
            $nombre_imagen = 'album_' . time() . '_' . rand(1000, 9999) . '.jpg';
            $imagen_data = file_get_contents($imagen_url);
            if ($imagen_data !== false) {
                file_put_contents('../uploads/' . $nombre_imagen, $imagen_data);
            } else {
                $nombre_imagen = null;
            }
        }
    }

    $sql = "INSERT INTO albumes (titulo, artista_id, imagen) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sis", $nombre_album, $artista_id, $nombre_imagen);
    mysqli_stmt_execute($stmt);
    $album_id = mysqli_insert_id($conexion);
    mysqli_stmt_close($stmt);
}

// ============================================================
// PASO 4: Verificar o crear la CANCIÓN
// ============================================================
$sql = "SELECT id FROM canciones WHERE LOWER(titulo) = LOWER(?) AND album_id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "si", $nombre_cancion, $album_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$cancion = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if ($cancion) {
    $cancion_id = $cancion['id'];
} else {
    $sql = "INSERT INTO canciones (titulo, duracion, album_id) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $nombre_cancion, $duracion, $album_id);
    mysqli_stmt_execute($stmt);
    $cancion_id = mysqli_insert_id($conexion);
    mysqli_stmt_close($stmt);
}

// ============================================================
// PASO 5: Agregar la canción a la PLAYLIST
// ============================================================
// Verificamos que no esté ya en la playlist
$sql = "SELECT id FROM playlist_canciones WHERE playlist_id = ? AND cancion_id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ii", $playlist_id, $cancion_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$ya_en_playlist = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if ($ya_en_playlist) {
    header("Location: " . $url_retorno . "&mensaje=ya_existe");
    exit;
}

$sql = "INSERT INTO playlist_canciones (playlist_id, cancion_id) VALUES (?, ?)";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ii", $playlist_id, $cancion_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: " . $url_retorno . "&mensaje=cancion_agregada");
exit;
?>