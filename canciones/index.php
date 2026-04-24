<?php
require '../conexion.php';

$sql = "SELECT canciones.*, albumes.titulo AS album_titulo, artistas.nombre AS artista_nombre 
        FROM canciones 
        JOIN albumes ON canciones.album_id = albumes.id 
        JOIN artistas ON albumes.artista_id = artistas.id 
        ORDER BY artistas.nombre ASC, albumes.titulo ASC, canciones.titulo ASC";
$resultado = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Música - Canciones</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Mis Canciones</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <?php if ($_GET['mensaje'] === 'cancion_guardada'): ?>
            <p class="mensaje-exito">¡Canción guardada exitosamente!</p>
        <?php elseif ($_GET['mensaje'] === 'cancion_actualizada'): ?>
            <p class="mensaje-exito">¡Canción actualizada exitosamente!</p>
        <?php elseif ($_GET['mensaje'] === 'cancion_eliminada'): ?>
            <p class="mensaje-exito" style="color: #ff9800;">Canción eliminada.</p>
        <?php endif; ?>
    <?php endif; ?>

    <a href="agregar.php" class="btn">+ Agregar nueva canción</a>

    <?php if (mysqli_num_rows($resultado) > 0): ?>

        <table>
            <thead>
                <tr>
                    <th>Canción</th>
                    <th>Álbum</th>
                    <th>Artista</th>
                    <th>Duración</th>
                    <th>Favorito</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cancion = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cancion['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($cancion['album_titulo']); ?></td>
                        <td><?php echo htmlspecialchars($cancion['artista_nombre']); ?></td>
                        <td><?php echo $cancion['duracion'] ? $cancion['duracion'] : '—'; ?></td>
                        <td><?php echo $cancion['es_favorito'] ? '⭐' : '—'; ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $cancion['id']; ?>">Editar</a>
                            |
                            <a href="eliminar.php?id=<?php echo $cancion['id']; ?>"
                               onclick="return confirm('¿Estás seguro de eliminar esta canción?')">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>No hay canciones registradas todavía.</p>
    <?php endif; ?>

</body>
</html>