<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO playlists (nombre, descripcion) VALUES (?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $nombre, $descripcion);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?mensaje=playlist_creada");
        exit;
    } else {
        echo "Error al guardar: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($stmt);

} else {
    header("Location: agregar.php");
    exit;
}
?>