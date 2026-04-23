<?php
require 'conexion.php';

if (!isset($_GET['id'])) {
    header("Location: albumes.php");
    exit;
}

$id = $_GET['id'];

// Verificamos si tiene canciones asociadas
$sql_check = "SELECT COUNT(*) AS total FROM canciones WHERE album_id = ?";
$stmt_check = mysqli_prepare($conexion, $sql_check);
mysqli_stmt_bind_param($stmt_check, "i", $id);
mysqli_stmt_execute($stmt_check);
$resultado = mysqli_stmt_get_result($stmt_check);
$fila = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt_check);

if ($fila['total'] > 0) {
    header("Location: albumes.php?mensaje=album_tiene_canciones");
    exit;
}

// Si no tiene canciones, eliminamos
$sql = "DELETE FROM albumes WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: albumes.php?mensaje=album_eliminado");
    exit;
} else {
    echo "Error al eliminar: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
?>