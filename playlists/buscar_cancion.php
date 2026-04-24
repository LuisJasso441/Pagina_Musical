<?php
require '../lastfm.php';
require '../conexion.php';

if (!isset($_GET['playlist_id'])) {
    header("Location: index.php");
    exit;
}

$playlist_id = $_GET['playlist_id'];

// Verificamos que la playlist existe
$sql = "SELECT * FROM playlists WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $playlist_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$playlist = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$playlist) {
    header("Location: index.php");
    exit;
}

// Búsqueda en Last.fm
$resultados = [];
$busqueda = '';

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $busqueda = $_GET['q'];
    $resultados = lastfm_buscar_canciones($busqueda, 15);
}

// Obtenemos las canciones que ya están en la playlist para marcarlas
$sql_existentes = "SELECT canciones.titulo, artistas.nombre AS artista_nombre
                   FROM playlist_canciones
                   JOIN canciones ON playlist_canciones.cancion_id = canciones.id
                   JOIN albumes ON canciones.album_id = albumes.id
                   JOIN artistas ON albumes.artista_id = artistas.id
                   WHERE playlist_canciones.playlist_id = ?";
$stmt_ex = mysqli_prepare($conexion, $sql_existentes);
mysqli_stmt_bind_param($stmt_ex, "i", $playlist_id);
mysqli_stmt_execute($stmt_ex);
$res_ex = mysqli_stmt_get_result($stmt_ex);
$existentes = [];
while ($fila = mysqli_fetch_assoc($res_ex)) {
    // Creamos una clave única: "artista|cancion" en minúsculas
    $existentes[] = strtolower($fila['artista_nombre'] . '|' . $fila['titulo']);
}
mysqli_stmt_close($stmt_ex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Canciones - <?php echo htmlspecialchars($playlist['nombre']); ?></title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <a href="detalle.php?id=<?php echo $playlist_id; ?>" class="volver-link">
        ← Volver a <?php echo htmlspecialchars($playlist['nombre']); ?>
    </a>

    <h1>Buscar Canciones en Last.fm</h1>
    <p class="detalle-meta">
        Agregando a: <strong><?php echo htmlspecialchars($playlist['nombre']); ?></strong>
    </p>

    <form method="GET" class="form-busqueda-api">
        <input type="hidden" name="playlist_id" value="<?php echo $playlist_id; ?>">
        <input type="text" name="q" placeholder="Busca una canción o artista..." 
               value="<?php echo htmlspecialchars($busqueda); ?>" required>
        <button type="submit" class="btn">Buscar</button>
    </form>

    <?php if (isset($_GET['mensaje'])): ?>
        <?php if ($_GET['mensaje'] === 'cancion_agregada'): ?>
            <p class="mensaje-exito">¡Canción agregada a la playlist!</p>
        <?php elseif ($_GET['mensaje'] === 'ya_existe'): ?>
            <p class="mensaje-exito" style="color: #ff9800;">Esa canción ya está en la playlist.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (count($resultados) > 0): ?>

        <h2 class="seccion-titulo">Resultados para "<?php echo htmlspecialchars($busqueda); ?>"</h2>

        <div class="resultados-canciones">
            <?php foreach ($resultados as $track): ?>
                <?php
                $nombre_cancion = $track['name'];
                $nombre_artista = $track['artist'];
                $clave = strtolower($nombre_artista . '|' . $nombre_cancion);
                $ya_agregada = in_array($clave, $existentes);
                ?>
                <div class="resultado-cancion <?php echo $ya_agregada ? 'resultado-agregado' : ''; ?>">
                    <div class="resultado-cancion-info">
                        <span class="resultado-cancion-titulo">
                            <?php echo htmlspecialchars($nombre_cancion); ?>
                        </span>
                        <span class="resultado-cancion-artista">
                            <?php echo htmlspecialchars($nombre_artista); ?>
                        </span>
                    </div>
                    <?php if ($ya_agregada): ?>
                        <span class="resultado-ya-agregado">✓ En playlist</span>
                    <?php else: ?>
                        <form action="importar_cancion.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="playlist_id" value="<?php echo $playlist_id; ?>">
                            <input type="hidden" name="cancion" 
                                   value="<?php echo htmlspecialchars($nombre_cancion); ?>">
                            <input type="hidden" name="artista" 
                                   value="<?php echo htmlspecialchars($nombre_artista); ?>">
                            <input type="hidden" name="busqueda" 
                                   value="<?php echo htmlspecialchars($busqueda); ?>">
                            <button type="submit" class="btn btn-pequeno">+ Agregar</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif (!empty($busqueda)): ?>
        <p>No se encontraron resultados para "<?php echo htmlspecialchars($busqueda); ?>".</p>
    <?php endif; ?>

</body>
</html>