<?php
require '../lastfm.php';
require '../conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test API - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Probar conexión con Last.fm</h1>

    <form method="GET" class="form-inline" style="margin-bottom: 20px;">
        <input type="text" name="artista" placeholder="Busca un artista..." 
               value="<?php echo isset($_GET['artista']) ? htmlspecialchars($_GET['artista']) : ''; ?>" 
               required>
        <button type="submit" class="btn btn-pequeno">Buscar</button>
    </form>

    <?php if (isset($_GET['artista']) && !empty($_GET['artista'])): ?>
        
        <?php $resultados = lastfm_buscar_artistas($_GET['artista'], 5); ?>

        <?php if (count($resultados) > 0): ?>
            
            <h2 class="seccion-titulo">Resultados para "<?php echo htmlspecialchars($_GET['artista']); ?>"</h2>

            <?php foreach ($resultados as $artista): ?>
                <div class="resultado-api">
                    <div class="resultado-info">
                        <strong><?php echo htmlspecialchars($artista['name']); ?></strong>
                        <span class="detalle-meta">
                            <?php echo number_format($artista['listeners']); ?> oyentes
                        </span>
                    </div>
                    <a href="ver_artista.php?nombre=<?php echo urlencode($artista['name']); ?>" 
                       class="btn btn-pequeno">
                        Ver álbumes
                    </a>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>No se encontraron resultados.</p>
        <?php endif; ?>

    <?php endif; ?>

</body>
</html>