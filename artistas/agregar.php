<?php require '../conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Artista - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Agregar Nuevo Artista</h1>

    <form action="guardar.php" method="POST" enctype="multipart/form-data">

        <label for="nombre">Nombre del artista:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="genero">Género musical:</label>
        <input type="text" id="genero" name="genero">

        <label for="imagen">Foto del artista:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">

        <label for="es_favorito">¿Es favorito?</label>
        <select id="es_favorito" name="es_favorito">
            <option value="0">No</option>
            <option value="1">Sí</option>
        </select>

        <button type="submit">Guardar Artista</button>

    </form>

    <a href="index.php">← Volver a la lista</a>

</body>
</html>