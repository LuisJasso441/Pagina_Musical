<?php
// Paso 1: Conexión
require 'conexion.php';

// Paso 2: Verificamos que llegó un ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Paso 3: Verificamos si el artista tiene álbumes asociados
$sql_check = "SELECT COUNT(*) AS total FROM albumes WHERE artista_id = ?";
$stmt_check = mysqli_prepare($conexion, $sql_check);
mysqli_stmt_bind_param($stmt_check, "i", $id);
mysqli_stmt_execute($stmt_check);
$resultado = mysqli_stmt_get_result($stmt_check);
$fila = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt_check);

// Paso 4: Si tiene álbumes, no dejamos eliminar
if ($fila['total'] > 0) {
    header("Location: index.php?mensaje=artista_tiene_albumes");
    exit;
}

// Paso 5: Si no tiene álbumes, lo eliminamos
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