<?php
require '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Primero eliminamos la canción de cualquier playlist
$sql_playlists = "DELETE FROM playlist_canciones WHERE cancion_id = ?";
$stmt_pl = mysqli_prepare($conexion, $sql_playlists);
mysqli_stmt_bind_param($stmt_pl, "i", $id);
mysqli_stmt_execute($stmt_pl);
mysqli_stmt_close($stmt_pl);

$sql = "DELETE FROM canciones WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?mensaje=cancion_eliminada");
    exit;
} else {
    echo "Error al eliminar: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
?>