<?php require '../auth/proteger.php'; ?>
<?php
require '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM playlists WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$playlist = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$playlist) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Playlist - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Editar Playlist</h1>

    <form action="actualizar.php" method="POST">

        <input type="hidden" name="id" value="<?php echo $playlist['id']; ?>">

        <label for="nombre">Nombre de la playlist:</label>
        <input type="text" id="nombre" name="nombre"
               value="<?php echo htmlspecialchars($playlist['nombre']); ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($playlist['descripcion']); ?></textarea>

        <button type="submit">Guardar Cambios</button>

    </form>

    <a href="index.php">← Volver a playlists</a>

</body>
</html>