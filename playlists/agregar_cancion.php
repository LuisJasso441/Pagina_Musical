<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $playlist_id = $_POST['playlist_id'];
    $cancion_id = $_POST['cancion_id'];

    // Verificamos que no esté ya en la playlist
    $sql_check = "SELECT id FROM playlist_canciones WHERE playlist_id = ? AND cancion_id = ?";
    $stmt_check = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "ii", $playlist_id, $cancion_id);
    mysqli_stmt_execute($stmt_check);
    $resultado = mysqli_stmt_get_result($stmt_check);
    mysqli_stmt_close($stmt_check);

    if (!mysqli_fetch_assoc($resultado)) {
        $sql = "INSERT INTO playlist_canciones (playlist_id, cancion_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $playlist_id, $cancion_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Si es una petición AJAX, respondemos con JSON
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
        strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false ||
        empty($_SERVER['HTTP_REFERER'])) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }

    // Si es formulario tradicional, redirigimos
    header("Location: detalle.php?id=" . $playlist_id . "&mensaje=cancion_agregada");
    exit;

} else {
    header("Location: index.php");
    exit;
}
?>