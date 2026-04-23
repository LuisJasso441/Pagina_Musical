<?php
require 'conexion.php';

if (!isset($_GET['id'])) {
    header("Location: canciones.php");
    exit;
}

$id = $_GET['id'];

// Las canciones no tienen "hijos", así que se eliminan directamente
$sql = "DELETE FROM canciones WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: canciones.php?mensaje=cancion_eliminada");
    exit;
} else {
    echo "Error al eliminar: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
?>