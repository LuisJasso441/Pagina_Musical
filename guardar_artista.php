<?php
// Paso 1: Traemos el archivo de conexión
require 'conexion.php';

// Paso 2: Verificamos que el formulario fue enviado con método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Paso 3: Recibimos los datos del formulario
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $es_favorito = $_POST['es_favorito'];

    // Paso 4: Preparamos la consulta SQL
    $sql = "INSERT INTO artistas (nombre, genero, es_favorito) VALUES (?, ?, ?)";

    // Paso 5: Creamos una "sentencia preparada"
    $stmt = mysqli_prepare($conexion, $sql);

    // Paso 6: Vinculamos los valores a los signos de interrogación
    mysqli_stmt_bind_param($stmt, "ssi", $nombre, $genero, $es_favorito);

    // Paso 7: Ejecutamos la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Si todo salió bien, redirigimos a la lista
        header("Location: index.php?mensaje=artista_guardado");
        exit;
    } else {
        echo "Error al guardar: " . mysqli_error($conexion);
    }

    // Paso 8: Cerramos la sentencia
    mysqli_stmt_close($stmt);

} else {
    // Si alguien intenta entrar directamente a este archivo sin enviar el formulario
    header("Location: agregar_artista.php");
    exit;
}
?>