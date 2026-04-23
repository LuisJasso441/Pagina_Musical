<?php
// Paso 1: Conexión
require 'conexion.php';

// Paso 2: Verificamos que el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Paso 3: Recibimos los datos (incluyendo el ID oculto)
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $es_favorito = $_POST['es_favorito'];

    // Paso 4: Preparamos la consulta de actualización
    $sql = "UPDATE artistas SET nombre = ?, genero = ?, es_favorito = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $sql);

    // Paso 5: Vinculamos los valores
    mysqli_stmt_bind_param($stmt, "ssii", $nombre, $genero, $es_favorito, $id);

    // Paso 6: Ejecutamos
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