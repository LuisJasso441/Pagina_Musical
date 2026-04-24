<?php
require '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

// Buscamos el artista
$id = $_GET['id'];
$sql = "SELECT * FROM artistas WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$artista = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$artista) {
    header("Location: index.php");
    exit;
}

// Álbumes del artista
$sql_albumes = "SELECT * FROM albumes WHERE artista_id = ? ORDER BY anio DESC";
$stmt_albumes = mysqli_prepare($conexion, $sql_albumes);
mysqli_stmt_bind_param($stmt_albumes, "i", $id);
mysqli_stmt_execute($stmt_albumes);
$resultado_albumes = mysqli_stmt_get_result($stmt_albumes);
$total_albumes = mysqli_num_rows($resultado_albumes);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($artista['nombre']); ?> - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <?php if (isset($_GET['mensaje'])): ?>
        <?php if ($_GET['mensaje'] === 'artista_importado'): ?>
            <p class="mensaje-exito">¡Artista importado exitosamente desde Last.fm!</p>
        <?php elseif ($_GET['mensaje'] === 'artista_existe'): ?>
            <p class="mensaje-exito" style="color: #ff9800;">Este artista ya existía en tu base de datos.</p>
        <?php elseif ($_GET['mensaje'] === 'albumes_importados'): ?>
            <p class="mensaje-exito">¡Álbumes importados exitosamente!</p>
        <?php endif; ?>
    <?php endif; ?>

    <a href="index.php" class="volver-link">← Volver a artistas</a>

    <!-- CABECERA DEL ARTISTA -->
    <div class="artista-header">
        <div class="artista-avatar">
            <?php echo mb_strtoupper(mb_substr($artista['nombre'], 0, 1)); ?>
        </div>
        <div>
            <h1 class="detalle-titulo">
                <?php echo htmlspecialchars($artista['nombre']); ?>
                <?php echo $artista['es_favorito'] ? ' ⭐' : ''; ?>
            </h1>
            <p class="detalle-artista">
                <?php echo $artista['genero'] ? htmlspecialchars($artista['genero']) : 'Sin género'; ?>
            </p>
            <p class="detalle-meta">
                <?php echo $total_albumes; ?> álbumes
            </p>
            <div class="detalle-acciones">
                <a href="editar.php?id=<?php echo $artista['id']; ?>" class="btn btn-pequeno">Editar artista</a>
                <a href="../api/importar_albumes.php?id=<?php echo $artista['id']; ?>&nombre=<?php echo urlencode($artista['nombre']); ?>" 
                   class="btn btn-pequeno btn-api">Importar álbumes de Last.fm</a>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE ÁLBUMES -->
    <h2 class="seccion-titulo">Álbumes</h2>

    <?php if ($total_albumes > 0): ?>

        <div class="album-grid">
            <?php while ($album = mysqli_fetch_assoc($resultado_albumes)): ?>
                <div class="album-card">
                    <a href="../albumes/detalle.php?id=<?php echo $album['id']; ?>" class="album-link">
                        <?php if ($album['imagen']): ?>
                            <img src="../uploads/<?php echo $album['imagen']; ?>" 
                                 alt="<?php echo htmlspecialchars($album['titulo']); ?>">
                        <?php else: ?>
                            <div class="album-sin-imagen">
                                <span>Sin portada</span>
                            </div>
                        <?php endif; ?>
                    </a>
                    <div class="album-info">
                        <h3>
                            <a href="../albumes/detalle.php?id=<?php echo $album['id']; ?>">
                                <?php echo htmlspecialchars($album['titulo']); ?>
                            </a>
                        </h3>
                        <p class="album-anio">
                            <?php echo $album['anio'] ? $album['anio'] : ''; ?>
                            <?php echo $album['es_favorito'] ? ' ⭐' : ''; ?>
                        </p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    <?php else: ?>
        <p>Este artista no tiene álbumes registrados.</p>
    <?php endif; ?>
</body>
</html>