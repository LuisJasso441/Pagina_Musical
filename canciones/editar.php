<?php require '../auth/proteger.php'; ?>
<?php
require '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM canciones WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$cancion = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$cancion) {
    header("Location: index.php");
    exit;
}

$sql_albumes = "SELECT albumes.id, albumes.titulo, artistas.nombre AS artista_nombre 
                FROM albumes 
                JOIN artistas ON albumes.artista_id = artistas.id 
                ORDER BY artistas.nombre ASC, albumes.titulo ASC";
$resultado_albumes = mysqli_query($conexion, $sql_albumes);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Canción - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Editar Canción</h1>

    <form action="actualizar.php" method="POST">

        <input type="hidden" name="id" value="<?php echo $cancion['id']; ?>">

        <label for="titulo">Título de la canción:</label>
        <input type="text" id="titulo" name="titulo"
               value="<?php echo htmlspecialchars($cancion['titulo']); ?>" required>

        <label for="duracion">Duración (ej: 3:45):</label>
        <input type="text" id="duracion" name="duracion"
               value="<?php echo htmlspecialchars($cancion['duracion']); ?>" placeholder="3:45">

        <label for="album_id">Álbum:</label>
        <select id="album_id" name="album_id" required>
            <option value="">-- Selecciona un álbum --</option>
            <?php while ($album = mysqli_fetch_assoc($resultado_albumes)): ?>
                <option value="<?php echo $album['id']; ?>"
                    <?php echo $album['id'] == $cancion['album_id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($album['artista_nombre'] . ' — ' . $album['titulo']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="es_favorito">¿Es favorito?</label>
        <select id="es_favorito" name="es_favorito">
            <option value="0" <?php echo $cancion['es_favorito'] == 0 ? 'selected' : ''; ?>>No</option>
            <option value="1" <?php echo $cancion['es_favorito'] == 1 ? 'selected' : ''; ?>>Sí</option>
        </select>

        <button type="submit">Guardar Cambios</button>

    </form>

    <a href="index.php">← Volver a la lista de canciones</a>

</body>
</html>