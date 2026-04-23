<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = $_POST['titulo'];
    $anio = $_POST['anio'];
    $artista_id = $_POST['artista_id'];
    $es_favorito = $_POST['es_favorito'];
    $nombre_imagen = NULL;

    // ===== PROCESAMIENTO DE LA IMAGEN =====

    // Verificamos si el usuario subió una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {

        // Datos del archivo subido
        $archivo_temporal = $_FILES['imagen']['tmp_name'];
        $nombre_original = $_FILES['imagen']['name'];
        $tamano = $_FILES['imagen']['size'];

        // Obtenemos la extensión del archivo (jpg, png, etc.)
        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

        // Extensiones permitidas
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        // Validamos la extensión
        if (!in_array($extension, $extensiones_permitidas)) {
            die("Error: Solo se permiten imágenes JPG, PNG, GIF o WEBP.");
        }

        // Validamos el tamaño (máximo 2MB)
        if ($tamano > 2 * 1024 * 1024) {
            die("Error: La imagen no debe superar los 2MB.");
        }

        // Generamos un nombre único para evitar conflictos
        $nombre_imagen = 'album_' . time() . '_' . rand(1000, 9999) . '.' . $extension;

        // Movemos el archivo de la ubicación temporal a nuestra carpeta
        $destino = 'uploads/' . $nombre_imagen;
        if (!move_uploaded_file($archivo_temporal, $destino)) {
            die("Error: No se pudo guardar la imagen.");
        }
    }

    // ===== GUARDAR EN LA BASE DE DATOS =====

    $sql = "INSERT INTO albumes (titulo, anio, artista_id, es_favorito, imagen) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "siiis", $titulo, $anio, $artista_id, $es_favorito, $nombre_imagen);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: albumes.php?mensaje=album_guardado");
        exit;
    } else {
        echo "Error al guardar: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($stmt);

} else {
    header("Location: agregar_album.php");
    exit;
}
?>