<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $duracion = $_POST['duracion'];
    $album_id = $_POST['album_id'];
    $es_favorito = $_POST['es_favorito'];

    $sql = "UPDATE canciones SET titulo = ?, duracion = ?, album_id = ?, es_favorito = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssiii", $titulo, $duracion, $album_id, $es_favorito, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: canciones.php?mensaje=cancion_actualizada");
        exit;
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($stmt);

} else {
    header("Location: canciones.php");
    exit;
}
?>