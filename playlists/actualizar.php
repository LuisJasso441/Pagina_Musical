<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $sql = "UPDATE playlists SET nombre = ?, descripcion = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $nombre, $descripcion, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?mensaje=playlist_actualizada");
        exit;
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($stmt);

} else {
    header("Location: index.php");
    exit;
}
?>