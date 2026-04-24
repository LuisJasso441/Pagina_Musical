<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: buscar_artista.php");
    exit;
}

$nombre = $_POST['nombre'];
$genero = $_POST['genero'];

// Verificamos si el artista ya existe para no duplicar
$sql_check = "SELECT id FROM artistas WHERE nombre = ?";
$stmt_check = mysqli_prepare($conexion, $sql_check);
mysqli_stmt_bind_param($stmt_check, "s", $nombre);
mysqli_stmt_execute($stmt_check);
$resultado = mysqli_stmt_get_result($stmt_check);
$existente = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt_check);

if ($existente) {
    // Si ya existe, redirigimos a su detalle
    header("Location: ../artistas/detalle.php?id=" . $existente['id'] . "&mensaje=artista_existe");
    exit;
}

// Insertamos el artista
$sql = "INSERT INTO artistas (nombre, genero) VALUES (?, ?)";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ss", $nombre, $genero);

if (mysqli_stmt_execute($stmt)) {
    $nuevo_id = mysqli_insert_id($conexion);
    mysqli_stmt_close($stmt);
    header("Location: ../artistas/detalle.php?id=" . $nuevo_id . "&mensaje=artista_importado");
    exit;
} else {
    echo "Error al guardar: " . mysqli_error($conexion);
}
?>