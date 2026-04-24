<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $es_favorito = $_POST['es_favorito'];

    $sql = "INSERT INTO artistas (nombre, genero, es_favorito) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $nombre, $genero, $es_favorito);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?mensaje=artista_guardado");
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