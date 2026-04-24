<?php
require '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql_check = "SELECT COUNT(*) AS total FROM albumes WHERE artista_id = ?";
$stmt_check = mysqli_prepare($conexion, $sql_check);
mysqli_stmt_bind_param($stmt_check, "i", $id);
mysqli_stmt_execute($stmt_check);
$resultado = mysqli_stmt_get_result($stmt_check);
$fila = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt_check);

if ($fila['total'] > 0) {
    header("Location: index.php?mensaje=artista_tiene_albumes");
    exit;
}

$sql = "DELETE FROM artistas WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?mensaje=artista_eliminado");
    exit;
} else {
    echo "Error al eliminar: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
?>