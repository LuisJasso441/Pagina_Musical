<?php
require '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql_check = "SELECT COUNT(*) AS total FROM canciones WHERE album_id = ?";
$stmt_check = mysqli_prepare($conexion, $sql_check);
mysqli_stmt_bind_param($stmt_check, "i", $id);
mysqli_stmt_execute($stmt_check);
$resultado = mysqli_stmt_get_result($stmt_check);
$fila = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt_check);

if ($fila['total'] > 0) {
    header("Location: index.php?mensaje=album_tiene_canciones");
    exit;
}

// Eliminamos la imagen si existe
$sql_img = "SELECT imagen FROM albumes WHERE id = ?";
$stmt_img = mysqli_prepare($conexion, $sql_img);
mysqli_stmt_bind_param($stmt_img, "i", $id);
mysqli_stmt_execute($stmt_img);
$res_img = mysqli_stmt_get_result($stmt_img);
$album = mysqli_fetch_assoc($res_img);
mysqli_stmt_close($stmt_img);

if ($album['imagen'] && file_exists('../uploads/' . $album['imagen'])) {
    unlink('../uploads/' . $album['imagen']);
}

$sql = "DELETE FROM albumes WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?mensaje=album_eliminado");
    exit;
} else {
    echo "Error al eliminar: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
?>