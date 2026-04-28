<?php
session_start();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['usuario_id'])) {
    // Calculamos la ruta al login
    $carpeta_actual = basename(dirname($_SERVER['PHP_SELF']));
    $subcarpetas = ['artistas', 'albumes', 'canciones', 'playlists', 'api'];

    if (in_array($carpeta_actual, $subcarpetas)) {
        header("Location: ../auth/login.php");
    } else {
        header("Location: auth/login.php");
    }
    exit;
}
?>