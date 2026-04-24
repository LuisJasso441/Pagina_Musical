<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $es_favorito = $_POST['es_favorito'];

    $sql = "UPDATE artistas SET nombre = ?, genero = ?, es_favorito = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $nombre, $genero, $es_favorito, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?mensaje=artista_actualizado");
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