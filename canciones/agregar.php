<?php
require '../conexion.php';

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
    <title>Agregar Canción - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Agregar Nueva Canción</h1>

    <form action="guardar.php" method="POST">

        <label for="titulo">Título de la canción:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="duracion">Duración (ej: 3:45):</label>
        <input type="text" id="duracion" name="duracion" placeholder="3:45">

        <label for="album_id">Álbum:</label>
        <select id="album_id" name="album_id" required>
            <option value="">-- Selecciona un álbum --</option>
            <?php while ($album = mysqli_fetch_assoc($resultado_albumes)): ?>
                <option value="<?php echo $album['id']; ?>">
                    <?php echo htmlspecialchars($album['artista_nombre'] . ' — ' . $album['titulo']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="es_favorito">¿Es favorito?</label>
        <select id="es_favorito" name="es_favorito">
            <option value="0">No</option>
            <option value="1">Sí</option>
        </select>

        <button type="submit">Guardar Canción</button>

    </form>

    <a href="index.php">← Volver a la lista de canciones</a>

</body>
</html>