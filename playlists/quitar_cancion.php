<?php
require '../conexion.php';

if (!isset($_GET['playlist_id']) || !isset($_GET['cancion_id'])) {
    header("Location: index.php");
    exit;
}

$playlist_id = $_GET['playlist_id'];
$cancion_id = $_GET['cancion_id'];

$sql = "DELETE FROM playlist_canciones WHERE playlist_id = ? AND cancion_id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ii", $playlist_id, $cancion_id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: detalle.php?id=" . $playlist_id . "&mensaje=cancion_removida");
    exit;
} else {
    echo "Error: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
?>