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

$sql_canciones = "SELECT canciones.*, albumes.titulo AS album_titulo, artistas.nombre AS artista_nombre 
                  FROM playlist_canciones 
                  JOIN canciones ON playlist_canciones.cancion_id = canciones.id 
                  JOIN albumes ON canciones.album_id = albumes.id 
                  JOIN artistas ON albumes.artista_id = artistas.id 
                  WHERE playlist_canciones.playlist_id = ? 
                  ORDER BY playlist_canciones.orden ASC, playlist_canciones.agregado_en ASC";
$stmt_canciones = mysqli_prepare($conexion, $sql_canciones);
mysqli_stmt_bind_param($stmt_canciones, "i", $id);
mysqli_stmt_execute($stmt_canciones);
$resultado_canciones = mysqli_stmt_get_result($stmt_canciones);
$total = mysqli_num_rows($resultado_canciones);

$sql_disponibles = "SELECT canciones.id, canciones.titulo, albumes.titulo AS album_titulo, 
                           artistas.nombre AS artista_nombre 
                    FROM canciones 
                    JOIN albumes ON canciones.album_id = albumes.id 
                    JOIN artistas ON albumes.artista_id = artistas.id 
                    WHERE canciones.id NOT IN (
                        SELECT cancion_id FROM playlist_canciones WHERE playlist_id = ?
                    ) 
                    ORDER BY artistas.nombre ASC, albumes.titulo ASC, canciones.titulo ASC";
$stmt_disp = mysqli_prepare($conexion, $sql_disponibles);
mysqli_stmt_bind_param($stmt_disp, "i", $id);
mysqli_stmt_execute($stmt_disp);
$resultado_disponibles = mysqli_stmt_get_result($stmt_disp);
$hay_disponibles = mysqli_num_rows($resultado_disponibles) > 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($playlist['nombre']); ?> - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <a href="index.php" class="volver-link">← Volver a playlists</a>

    <div class="playlist-header">
        <div class="playlist-icono-grande">🎶</div>
        <div>
            <h1 class="detalle-titulo"><?php echo htmlspecialchars($playlist['nombre']); ?></h1>
            <p class="detalle-meta">
                <?php echo $playlist['descripcion'] ? htmlspecialchars($playlist['descripcion']) : ''; ?>
            </p>
            <p class="detalle-meta"><?php echo $total; ?> canciones</p>
        </div>
    </div>

    <?php if (isset($_GET['mensaje'])): ?>
        <?php if ($_GET['mensaje'] === 'cancion_agregada'): ?>
            <p class="mensaje-exito">¡Canción agregada a la playlist!</p>
        <?php elseif ($_GET['mensaje'] === 'cancion_removida'): ?>
            <p class="mensaje-exito" style="color: #ff9800;">Canción removida de la playlist.</p>
        <?php endif; ?>
    <?php endif; ?>

    <div class="agregar-a-playlist">
        <a href="buscar_cancion.php?playlist_id=<?php echo $playlist['id']; ?>" class="btn btn-api">
            🔍 Buscar en Last.fm
        </a>

        <?php if ($hay_disponibles): ?>
            <form action="agregar_cancion.php" method="POST" class="form-inline" style="margin-top: 10px;">
                <input type="hidden" name="playlist_id" value="<?php echo $playlist['id']; ?>">
                <select name="cancion_id" required>
                    <option value="">-- Agregar de mi colección --</option>
                    <?php while ($disponible = mysqli_fetch_assoc($resultado_disponibles)): ?>
                        <option value="<?php echo $disponible['id']; ?>">
                            <?php echo htmlspecialchars($disponible['artista_nombre'] . ' — ' . $disponible['titulo']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" class="btn btn-pequeno">+ Agregar</button>
            </form>
        <?php endif; ?>
    </div>

    <?php if ($total > 0): ?>
        <div class="lista-canciones">
            <?php 
            mysqli_data_seek($resultado_canciones, 0);
            $numero = 1;
            while ($cancion = mysqli_fetch_assoc($resultado_canciones)): 
            ?>
                <div class="cancion-fila">
                    <span class="cancion-numero"><?php echo $numero; ?></span>
                    <div class="cancion-info-playlist">
                        <span class="cancion-titulo"><?php echo htmlspecialchars($cancion['titulo']); ?></span>
                        <span class="cancion-artista-album">
                            <?php echo htmlspecialchars($cancion['artista_nombre'] . ' · ' . $cancion['album_titulo']); ?>
                        </span>
                    </div>
                    <span class="cancion-duracion">
                        <?php echo $cancion['duracion'] ? $cancion['duracion'] : '—'; ?>
                    </span>
                    <a href="quitar_cancion.php?playlist_id=<?php echo $playlist['id']; ?>&cancion_id=<?php echo $cancion['id']; ?>"
                       class="btn-quitar"
                       onclick="return confirm('¿Quitar esta canción de la playlist?')">
                        ✕
                    </a>
                </div>
            <?php $numero++; endwhile; ?>
        </div>
    <?php endif; ?>

</body>
</html>