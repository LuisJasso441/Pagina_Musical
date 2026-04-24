<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $es_favorito = $_POST['es_favorito'];

    $nombre_imagen = NULL;
    $imagen_nueva = false;

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

        // Eliminamos la imagen anterior
        $sql_img = "SELECT imagen FROM artistas WHERE id = ?";
        $stmt_img = mysqli_prepare($conexion, $sql_img);
        mysqli_stmt_bind_param($stmt_img, "i", $id);
        mysqli_stmt_execute($stmt_img);
        $res_img = mysqli_stmt_get_result($stmt_img);
        $artista_actual = mysqli_fetch_assoc($res_img);
        mysqli_stmt_close($stmt_img);

        if ($artista_actual['imagen'] && file_exists('../uploads/' . $artista_actual['imagen'])) {
            unlink('../uploads/' . $artista_actual['imagen']);
        }

        $imagen_nueva = true;
    }

    if ($imagen_nueva) {
        $sql = "UPDATE artistas SET nombre = ?, genero = ?, es_favorito = ?, imagen = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssisi", $nombre, $genero, $es_favorito, $nombre_imagen, $id);
    } else {
        $sql = "UPDATE artistas SET nombre = ?, genero = ?, es_favorito = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $nombre, $genero, $es_favorito, $id);
    }

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