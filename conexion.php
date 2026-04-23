<?php
// Datos de conexión a la base de datos
$servidor = "localhost";    // XAMPP corre MySQL en tu máquina local
$usuario = "root";          // Usuario por defecto de XAMPP
$password = "";             // XAMPP no pone contraseña por defecto
$base_datos = "mi_musica";  // El nombre de la base que creamos

// Intentamos conectarnos usando MySQLi
$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

// Verificamos si la conexión falló
if (!$conexion) {
    // Si falló, mostramos el error y detenemos todo
    die("Error de conexión: " . mysqli_connect_error());
}

// Configuramos que use caracteres UTF-8 (para acentos y ñ)
mysqli_set_charset($conexion, "utf8");
?>