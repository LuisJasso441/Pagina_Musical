<?php require '../auth/proteger.php'; ?>
<?php
require '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM artistas WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$artista = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$artista) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artista - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Editar Artista</h1>

    <form action="actualizar.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?php echo $artista['id']; ?>">

        <label for="nombre">Nombre del artista:</label>
        <input type="text" id="nombre" name="nombre"
               value="<?php echo htmlspecialchars($artista['nombre']); ?>" required>

        <label for="genero">Género musical:</label>
        <input type="text" id="genero" name="genero"
               value="<?php echo htmlspecialchars($artista['genero']); ?>">

        <label for="imagen">Foto del artista:</label>
        <?php if ($artista['imagen']): ?>
            <div class="imagen-actual">
                <img src="../uploads/<?php echo $artista['imagen']; ?>" alt="Foto actual" width="150" style="border-radius: 50%;">
                <p>Foto actual. Selecciona otra imagen para reemplazarla.</p>
            </div>
        <?php endif; ?>
        <input type="file" id="imagen" name="imagen" accept="image/*">

        <label for="es_favorito">¿Es favorito?</label>
        <select id="es_favorito" name="es_favorito">
            <option value="0" <?php echo $artista['es_favorito'] == 0 ? 'selected' : ''; ?>>No</option>
            <option value="1" <?php echo $artista['es_favorito'] == 1 ? 'selected' : ''; ?>>Sí</option>
        </select>

        <button type="submit">Guardar Cambios</button>

    </form>

    <a href="index.php">← Volver a la lista</a>

</body>
</html>