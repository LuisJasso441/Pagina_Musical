<?php
session_start();

// Destruimos toda la sesión
session_destroy();

// Redirigimos al login
header("Location: login.php");
exit;
?>