<?php require '../auth/proteger.php'; ?>
<?php
require '../lastfm.php';
require '../conexion.php';

$resultados = [];
$busqueda = '';

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $busqueda = $_GET['q'];
    $resultados = lastfm_buscar_artistas($busqueda, 8);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Artista - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Buscar Artista en Last.fm</h1>

    <form method="GET" class="form-inline" style="margin-bottom: 20px;">
        <input type="text" name="q" placeholder="Nombre del artista..." 
               value="<?php echo htmlspecialchars($busqueda); ?>" required>
        <button type="submit" class="btn btn-pequeno">Buscar</button>
    </form>

    <?php if (count($resultados) > 0): ?>

        <div class="resultados-importar">
            <?php foreach ($resultados as $artista): ?>
                <?php
                // Obtenemos info detallada para extraer los tags
                $info = lastfm_info_artista($artista['name']);
                $genero = '';
                if ($info && isset($info['tags']['tag']) && count($info['tags']['tag']) > 0) {
                    $genero = $info['tags']['tag'][0]['name'];
                }
                ?>
                <div class="resultado-api">
                    <div class="resultado-info">
                        <strong><?php echo htmlspecialchars($artista['name']); ?></strong>
                        <span class="detalle-meta">
                            <?php echo $genero ? htmlspecialchars($genero) : 'Sin género'; ?>
                            · <?php echo number_format($artista['listeners']); ?> oyentes
                        </span>
                    </div>
                    <form action="importar_artista.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="nombre" 
                               value="<?php echo htmlspecialchars($artista['name']); ?>">
                        <input type="hidden" name="genero" 
                               value="<?php echo htmlspecialchars($genero); ?>">
                        <button type="submit" class="btn btn-pequeno">+ Importar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif (!empty($busqueda)): ?>
        <p>No se encontraron resultados para "<?php echo htmlspecialchars($busqueda); ?>".</p>
    <?php endif; ?>

    <a href="../artistas/index.php" class="volver-link">← Volver a artistas</a>

</body>
</html>