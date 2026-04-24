<?php
require '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql_limpiar = "DELETE FROM playlist_canciones WHERE playlist_id = ?";
$stmt_limpiar = mysqli_prepare($conexion, $sql_limpiar);
mysqli_stmt_bind_param($stmt_limpiar, "i", $id);
mysqli_stmt_execute($stmt_limpiar);
mysqli_stmt_close($stmt_limpiar);

$sql = "DELETE FROM playlists WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?mensaje=playlist_eliminada");
    exit;
} else {
    echo "Error al eliminar: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
?>