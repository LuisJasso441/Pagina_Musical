<?php require '../auth/proteger.php'; ?>
<?php
require '../conexion.php';

$sql_artistas = "SELECT id, nombre FROM artistas ORDER BY nombre ASC";
$resultado_artistas = mysqli_query($conexion, $sql_artistas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Álbum - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Agregar Nuevo Álbum</h1>

    <form action="guardar.php" method="POST" enctype="multipart/form-data">

        <label for="titulo">Título del álbum:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="anio">Año de lanzamiento:</label>
        <input type="number" id="anio" name="anio" min="1900" max="2030">

        <label for="artista_id">Artista:</label>
        <select id="artista_id" name="artista_id" required>
            <option value="">-- Selecciona un artista --</option>
            <?php while ($artista = mysqli_fetch_assoc($resultado_artistas)): ?>
                <option value="<?php echo $artista['id']; ?>">
                    <?php echo htmlspecialchars($artista['nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="imagen">Portada del álbum:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">

        <label for="es_favorito">¿Es favorito?</label>
        <select id="es_favorito" name="es_favorito">
            <option value="0">No</option>
            <option value="1">Sí</option>
        </select>

        <button type="submit">Guardar Álbum</button>

    </form>

    <a href="index.php">← Volver a la lista de álbumes</a>

</body>
</html>