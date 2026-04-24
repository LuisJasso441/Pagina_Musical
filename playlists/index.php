<?php
require '../conexion.php';

$sql = "SELECT playlists.*, COUNT(playlist_canciones.id) AS total_canciones 
        FROM playlists 
        LEFT JOIN playlist_canciones ON playlists.id = playlist_canciones.playlist_id 
        GROUP BY playlists.id 
        ORDER BY playlists.nombre ASC";
$resultado = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Música - Playlists</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Mis Playlists</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <?php if ($_GET['mensaje'] === 'playlist_creada'): ?>
            <p class="mensaje-exito">¡Playlist creada exitosamente!</p>
        <?php elseif ($_GET['mensaje'] === 'playlist_actualizada'): ?>
            <p class="mensaje-exito">¡Playlist actualizada exitosamente!</p>
        <?php elseif ($_GET['mensaje'] === 'playlist_eliminada'): ?>
            <p class="mensaje-exito" style="color: #ff9800;">Playlist eliminada.</p>
        <?php endif; ?>
    <?php endif; ?>

    <a href="agregar.php" class="btn">+ Crear nueva playlist</a>

    <?php if (mysqli_num_rows($resultado) > 0): ?>

        <div class="playlist-grid">
            <?php while ($playlist = mysqli_fetch_assoc($resultado)): ?>
                <div class="playlist-card">
                    <a href="detalle.php?id=<?php echo $playlist['id']; ?>" class="playlist-link">
                        <div class="playlist-icono">🎶</div>
                    </a>
                    <div class="playlist-info">
                        <h3>
                            <a href="detalle.php?id=<?php echo $playlist['id']; ?>">
                                <?php echo htmlspecialchars($playlist['nombre']); ?>
                            </a>
                        </h3>
                        <p class="playlist-descripcion">
                            <?php echo $playlist['descripcion'] ? htmlspecialchars($playlist['descripcion']) : 'Sin descripción'; ?>
                        </p>
                        <p class="playlist-meta">
                            <?php echo $playlist['total_canciones']; ?> canciones
                        </p>
                        <div class="album-acciones">
                            <a href="editar.php?id=<?php echo $playlist['id']; ?>">Editar</a>
                            <a href="eliminar.php?id=<?php echo $playlist['id']; ?>"
                               class="btn-eliminar"
                               onclick="return confirm('¿Estás seguro de eliminar esta playlist?')">
                                Eliminar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    <?php else: ?>
        <p>No hay playlists todavía. ¡Crea tu primera!</p>
    <?php endif; ?>

</body>
</html>