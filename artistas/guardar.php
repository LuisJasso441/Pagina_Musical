<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $es_favorito = $_POST['es_favorito'];
    $nombre_imagen = NULL;

    // Procesamiento de la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {

        $archivo_temporal = $_FILES['imagen']['tmp_name'];
        $nombre_original = $_FILES['imagen']['name'];
        $tamano = $_FILES['imagen']['size'];
        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $extensiones_permitidas)) {
            die("Error: Solo se permiten imágenes JPG, PNG, GIF o WEBP.");
        }

        if ($tamano > 2 * 1024 * 1024) {
            die("Error: La imagen no debe superar los 2MB.");
        }

        $nombre_imagen = 'artista_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
        $destino = '../uploads/' . $nombre_imagen;

        if (!move_uploaded_file($archivo_temporal, $destino)) {
            die("Error: No se pudo guardar la imagen.");
        }
    }

    $sql = "INSERT INTO artistas (nombre, genero, es_favorito, imagen) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssis", $nombre, $genero, $es_favorito, $nombre_imagen);

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