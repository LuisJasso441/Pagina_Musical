<?php require '../conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Playlist - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Crear Nueva Playlist</h1>

    <form action="guardar.php" method="POST">

        <label for="nombre">Nombre de la playlist:</label>
        <input type="text" id="nombre" name="nombre" required 
               placeholder="Ej: Mix para correr">

        <label for="descripcion">Descripción (opcional):</label>
        <textarea id="descripcion" name="descripcion" rows="3" 
                  placeholder="De qué trata esta playlist..."></textarea>

        <button type="submit">Crear Playlist</button>

    </form>

    <a href="index.php">← Volver a playlists</a>

</body>
</html>