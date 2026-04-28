<?php
require '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo = $_POST['tipo'];
    $categoria = $_POST['categoria'];
    $titulo = trim($_POST['titulo']);
    $artista = trim($_POST['artista'] ?? '');
    $descripcion = trim($_POST['descripcion']);
    $anio = $_POST['anio'];

    // Construir el periodo según el tipo
    if ($tipo === 'mes') {
        $mes = str_pad($_POST['mes'], 2, '0', STR_PAD_LEFT);
        $periodo = $anio . '-' . $mes;
    } else {
        $periodo = $anio;
    }

    $sql = "INSERT INTO descubrimientos (tipo, categoria, periodo, titulo, artista, descripcion) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $tipo, $categoria, $periodo, $titulo, $artista, $descripcion);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($stmt);

} else {
    header("Location: agregar.php");
    exit;
}
?>