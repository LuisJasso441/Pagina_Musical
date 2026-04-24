<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $playlist_id = $_POST['playlist_id'];
    $cancion_id = $_POST['cancion_id'];

    $sql = "INSERT INTO playlist_canciones (playlist_id, cancion_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $playlist_id, $cancion_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: detalle.php?id=" . $playlist_id . "&mensaje=cancion_agregada");
        exit;
    } else {
        echo "Error: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($stmt);

} else {
    header("Location: index.php");
    exit;
}
?>