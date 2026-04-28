<?php
require 'conexion.php';

// Estadísticas generales
$stats = [];

$resultado = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM artistas");
$stats['artistas'] = mysqli_fetch_assoc($resultado)['total'];

$resultado = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM albumes");
$stats['albumes'] = mysqli_fetch_assoc($resultado)['total'];

$resultado = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM canciones");
$stats['canciones'] = mysqli_fetch_assoc($resultado)['total'];

$resultado = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM playlists");
$stats['playlists'] = mysqli_fetch_assoc($resultado)['total'];

$resultado = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM canciones WHERE es_favorito = 1");
$stats['favoritas'] = mysqli_fetch_assoc($resultado)['total'];

// Últimos álbumes agregados (con portada)
$sql_recientes = "SELECT albumes.*, artistas.nombre AS artista_nombre 
                  FROM albumes 
                  JOIN artistas ON albumes.artista_id = artistas.id 
                  ORDER BY albumes.creado_en DESC 
                  LIMIT 6";
$resultado_recientes = mysqli_query($conexion, $sql_recientes);

// Canciones favoritas recientes
$sql_favs = "SELECT canciones.titulo, artistas.nombre AS artista_nombre, albumes.titulo AS album_titulo
             FROM canciones 
             JOIN albumes ON canciones.album_id = albumes.id 
             JOIN artistas ON albumes.artista_id = artistas.id 
             WHERE canciones.es_favorito = 1 
             ORDER BY canciones.creado_en DESC 
             LIMIT 5";
$resultado_favs = mysqli_query($conexion, $sql_favs);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Chill - Mi Colección Musical</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <!-- HERO / BIENVENIDA -->
    <div class="hero">
        <h1 class="hero-titulo">🎵 Music Chill</h1>
        <p class="hero-subtitulo">Mi colección musical personal</p>
    </div>

    <!-- ESTADÍSTICAS -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-numero"><?php echo $stats['artistas']; ?></span>
            <span class="stat-label">Artistas</span>
        </div>
        <div class="stat-card">
            <span class="stat-numero"><?php echo $stats['albumes']; ?></span>
            <span class="stat-label">Álbumes</span>
        </div>
        <div class="stat-card">
            <span class="stat-numero"><?php echo $stats['canciones']; ?></span>
            <span class="stat-label">Canciones</span>
        </div>
        <div class="stat-card">
            <span class="stat-numero"><?php echo $stats['favoritas']; ?></span>
            <span class="stat-label">⭐ Favoritas</span>
        </div>
        <div class="stat-card">
            <span class="stat-numero"><?php echo $stats['playlists']; ?></span>
            <span class="stat-label">Playlists</span>
        </div>
    </div>

    <!-- FAVORITAS RECIENTES -->
    <?php if (mysqli_num_rows($resultado_favs) > 0): ?>
        <h2 class="seccion-titulo">⭐ Favoritas recientes</h2>
        <div class="lista-canciones">
            <?php 
            $numero = 1;
            while ($cancion = mysqli_fetch_assoc($resultado_favs)): 
            ?>
                <div class="cancion-fila">
                    <span class="cancion-numero"><?php echo $numero; ?></span>
                    <div class="cancion-info-playlist">
                        <span class="cancion-titulo"><?php echo htmlspecialchars($cancion['titulo']); ?> ⭐</span>
                        <span class="cancion-artista-album">
                            <?php echo htmlspecialchars($cancion['artista_nombre'] . ' · ' . $cancion['album_titulo']); ?>
                        </span>
                    </div>
                </div>
            <?php $numero++; endwhile; ?>
        </div>
        <a href="canciones/index.php" class="btn" style="margin-top: 15px;">Ver todas las favoritas</a>
    <?php endif; ?>
    
    <!-- ÁLBUMES RECIENTES -->
    <?php if (mysqli_num_rows($resultado_recientes) > 0): ?>
        <h2 class="seccion-titulo">Últimos álbumes agregados</h2>
        <div class="album-grid">
            <?php while ($album = mysqli_fetch_assoc($resultado_recientes)): ?>
                <div class="album-card">
                    <a href="albumes/detalle.php?id=<?php echo $album['id']; ?>" class="album-link">
                        <?php if ($album['imagen']): ?>
                            <img src="uploads/<?php echo $album['imagen']; ?>" 
                                 alt="<?php echo htmlspecialchars($album['titulo']); ?>">
                        <?php else: ?>
                            <div class="album-sin-imagen">
                                <span>Sin portada</span>
                            </div>
                        <?php endif; ?>
                    </a>
                    <div class="album-info">
                        <h3>
                            <a href="albumes/detalle.php?id=<?php echo $album['id']; ?>">
                                <?php echo htmlspecialchars($album['titulo']); ?>
                            </a>
                        </h3>
                        <p class="album-artista"><?php echo htmlspecialchars($album['artista_nombre']); ?></p>
                        <p class="album-anio">
                            <?php echo $album['anio'] ? $album['anio'] : ''; ?>
                        </p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
    
</body>
</html>