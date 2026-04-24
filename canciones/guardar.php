<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = $_POST['titulo'];
    $duracion = $_POST['duracion'];
    $album_id = $_POST['album_id'];
    $es_favorito = $_POST['es_favorito'];

    $sql = "INSERT INTO canciones (titulo, duracion, album_id, es_favorito) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $titulo, $duracion, $album_id, $es_favorito);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?mensaje=cancion_guardada");
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