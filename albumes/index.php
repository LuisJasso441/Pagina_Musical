<?php require '../auth/proteger.php'; ?>
<?php
require '../conexion.php';

$sql = "SELECT albumes.*, artistas.nombre AS artista_nombre 
        FROM albumes 
        JOIN artistas ON albumes.artista_id = artistas.id 
        ORDER BY artistas.nombre ASC, albumes.anio DESC";
$resultado = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Música - Álbumes</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Mis Álbumes</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <?php if ($_GET['mensaje'] === 'album_guardado'): ?>
            <p class="mensaje-exito">¡Álbum guardado exitosamente!</p>
        <?php elseif ($_GET['mensaje'] === 'album_actualizado'): ?>
            <p class="mensaje-exito">¡Álbum actualizado exitosamente!</p>
        <?php elseif ($_GET['mensaje'] === 'album_eliminado'): ?>
            <p class="mensaje-exito" style="color: #ff9800;">Álbum eliminado.</p>
        <?php elseif ($_GET['mensaje'] === 'album_tiene_canciones'): ?>
            <p class="mensaje-error">No se puede eliminar: este álbum tiene canciones asociadas.</p>
        <?php endif; ?>
    <?php endif; ?>

    <a href="agregar.php" class="btn">+ Agregar nuevo álbum</a>

    <?php if (mysqli_num_rows($resultado) > 0): ?>

        <div class="album-grid">
            <?php while ($album = mysqli_fetch_assoc($resultado)): ?>
                <div class="album-card">
                    <a href="detalle.php?id=<?php echo $album['id']; ?>" class="album-link">
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
                            <a href="detalle.php?id=<?php echo $album['id']; ?>">
                                <?php echo htmlspecialchars($album['titulo']); ?>
                            </a>
                        </h3>
                        <p class="album-artista"><?php echo htmlspecialchars($album['artista_nombre']); ?></p>
                        <p class="album-anio">
                            <?php echo $album['anio'] ? $album['anio'] : ''; ?>
                            <?php echo $album['es_favorito'] ? ' ⭐' : ''; ?>
                        </p>
                        <div class="album-acciones">
                            <a href="editar.php?id=<?php echo $album['id']; ?>">Editar</a>
                            <a href="eliminar.php?id=<?php echo $album['id']; ?>"
                               class="btn-eliminar"
                               onclick="return confirm('¿Estás seguro de eliminar este álbum?')">
                                Eliminar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

    <?php else: ?>
        <p>No hay álbumes registrados todavía.</p>
    <?php endif; ?>

</body>
</html>