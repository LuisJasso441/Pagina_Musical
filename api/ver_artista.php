<?php
require '../lastfm.php';
require '../conexion.php';

if (!isset($_GET['nombre'])) {
    header("Location: test.php");
    exit;
}

$nombre_artista = $_GET['nombre'];

// Obtenemos info del artista
$info = lastfm_info_artista($nombre_artista);

// Obtenemos sus álbumes
$albumes = lastfm_albumes_artista($nombre_artista, 20);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($nombre_artista); ?> - API Test</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <a href="test.php" class="volver-link">← Volver a búsqueda</a>

    <h1><?php echo htmlspecialchars($nombre_artista); ?></h1>

    <?php if ($info): ?>
        <p class="detalle-meta">
            <?php 
            // Extraemos los tags/géneros del artista
            $generos = [];
            if (isset($info['tags']['tag'])) {
                foreach ($info['tags']['tag'] as $tag) {
                    $generos[] = $tag['name'];
                }
            }
            echo count($generos) > 0 ? htmlspecialchars(implode(', ', $generos)) : 'Sin géneros';
            ?>
        </p>
    <?php endif; ?>

    <h2 class="seccion-titulo">Álbumes encontrados (<?php echo count($albumes); ?>)</h2>

    <?php if (count($albumes) > 0): ?>

        <div class="album-grid">
            <?php foreach ($albumes as $album): ?>
                <?php 
                // Last.fm a veces incluye un álbum "(null)" que hay que filtrar
                if ($album['name'] === '(null)') continue;

                // Buscamos la imagen mediana
                $imagen_url = '';
                if (isset($album['image'])) {
                    foreach ($album['image'] as $img) {
                        if ($img['size'] === 'large') {
                            $imagen_url = $img['#text'];
                        }
                    }
                }
                ?>
                <div class="album-card">
                    <div class="album-link">
                        <?php if ($imagen_url): ?>
                            <img src="<?php echo $imagen_url; ?>" 
                                 alt="<?php echo htmlspecialchars($album['name']); ?>">
                        <?php else: ?>
                            <div class="album-sin-imagen">
                                <span>Sin portada</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="album-info">
                        <h3><?php echo htmlspecialchars($album['name']); ?></h3>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <p>No se encontraron álbumes.</p>
    <?php endif; ?>

</body>
</html>