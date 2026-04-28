<?php
require '../conexion.php';

// Indicamos que la respuesta es JSON
header('Content-Type: application/json');

$playlist_id = isset($_GET['playlist_id']) ? (int) $_GET['playlist_id'] : 0;
$termino = isset($_GET['q']) ? trim($_GET['q']) : '';

// Si el término es muy corto, no buscamos
if (strlen($termino) < 2) {
    echo json_encode([]);
    exit;
}

// Buscamos canciones que coincidan y que NO estén en la playlist
$busqueda = '%' . $termino . '%';

$sql = "SELECT canciones.id, canciones.titulo, canciones.duracion,
               albumes.titulo AS album_titulo, artistas.nombre AS artista_nombre
        FROM canciones
        JOIN albumes ON canciones.album_id = albumes.id
        JOIN artistas ON albumes.artista_id = artistas.id
        WHERE (canciones.titulo LIKE ? OR artistas.nombre LIKE ? OR albumes.titulo LIKE ?)
        AND canciones.id NOT IN (
            SELECT cancion_id FROM playlist_canciones WHERE playlist_id = ?
        )
        ORDER BY artistas.nombre ASC, canciones.titulo ASC
        LIMIT 10";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "sssi", $busqueda, $busqueda, $busqueda, $playlist_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$canciones = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $canciones[] = [
        'id' => $fila['id'],
        'titulo' => $fila['titulo'],
        'artista' => $fila['artista_nombre'],
        'album' => $fila['album_titulo'],
        'duracion' => $fila['duracion'] ? $fila['duracion'] : ''
    ];
}

mysqli_stmt_close($stmt);

echo json_encode($canciones);
?>