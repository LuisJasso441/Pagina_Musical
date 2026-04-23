<?php
require 'conexion.php';

if (!isset($_GET['id'])) {
    header("Location: albumes.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM albumes WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$album = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$album) {
    header("Location: albumes.php");
    exit;
}

$sql_artistas = "SELECT id, nombre FROM artistas ORDER BY nombre ASC";
$resultado_artistas = mysqli_query($conexion, $sql_artistas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Álbum - Mi Música</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <h1>Editar Álbum</h1>

    <form action="actualizar_album.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?php echo $album['id']; ?>">

        <label for="titulo">Título del álbum:</label>
        <input type="text" id="titulo" name="titulo"
               value="<?php echo htmlspecialchars($album['titulo']); ?>" required>

        <label for="anio">Año de lanzamiento:</label>
        <input type="number" id="anio" name="anio" min="1900" max="2030"
               value="<?php echo $album['anio']; ?>">

        <label for="artista_id">Artista:</label>
        <select id="artista_id" name="artista_id" required>
            <option value="">-- Selecciona un artista --</option>
            <?php while ($artista = mysqli_fetch_assoc($resultado_artistas)): ?>
                <option value="<?php echo $artista['id']; ?>"
                    <?php echo $artista['id'] == $album['artista_id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($artista['nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="imagen">Portada del álbum:</label>
        <?php if ($album['imagen']): ?>
            <div class="imagen-actual">
                <img src="uploads/<?php echo $album['imagen']; ?>" alt="Portada actual" width="150">
                <p>Portada actual. Selecciona otra imagen para reemplazarla.</p>
            </div>
        <?php endif; ?>
        <input type="file" id="imagen" name="imagen" accept="image/*">

        <label for="es_favorito">¿Es favorito?</label>
        <select id="es_favorito" name="es_favorito">
            <option value="0" <?php echo $album['es_favorito'] == 0 ? 'selected' : ''; ?>>No</option>
            <option value="1" <?php echo $album['es_favorito'] == 1 ? 'selected' : ''; ?>>Sí</option>
        </select>

        <button type="submit">Guardar Cambios</button>

    </form>

    <a href="albumes.php">← Volver a la lista de álbumes</a>

</body>
</html>