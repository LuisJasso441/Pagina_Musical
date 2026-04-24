<?php
require '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT albumes.*, artistas.nombre AS artista_nombre 
        FROM albumes 
        JOIN artistas ON albumes.artista_id = artistas.id 
        WHERE albumes.id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$album = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$album) {
    header("Location: index.php");
    exit;
}

$sql_canciones = "SELECT * FROM canciones WHERE album_id = ? ORDER BY id ASC";
$stmt_canciones = mysqli_prepare($conexion, $sql_canciones);
mysqli_stmt_bind_param($stmt_canciones, "i", $id);
mysqli_stmt_execute($stmt_canciones);
$resultado_canciones = mysqli_stmt_get_result($stmt_canciones);
$total_canciones = mysqli_num_rows($resultado_canciones);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($album['titulo']); ?> - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <a href="index.php" class="volver-link">← Volver a álbumes</a>

    <div class="detalle-album">
        <div class="detalle-portada">
            <?php if ($album['imagen']): ?>
                <img src="../uploads/<?php echo $album['imagen']; ?>" 
                     alt="<?php echo htmlspecialchars($album['titulo']); ?>">
            <?php else: ?>
                <div class="album-sin-imagen detalle-sin-imagen">
                    <span>Sin portada</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="detalle-info">
            <h1 class="detalle-titulo"><?php echo htmlspecialchars($album['titulo']); ?></h1>
            <p class="detalle-artista"><?php echo htmlspecialchars($album['artista_nombre']); ?></p>
            <p class="detalle-meta">
                <?php echo $album['anio'] ? $album['anio'] : 'Año desconocido'; ?>
                · <?php echo $total_canciones; ?> canciones
                <?php echo $album['es_favorito'] ? ' · ⭐ Favorito' : ''; ?>
            </p>
            <div class="detalle-acciones">
                <a href="editar.php?id=<?php echo $album['id']; ?>" class="btn btn-pequeno">Editar álbum</a>
            </div>
        </div>
    </div>

    <?php if ($total_canciones > 0): ?>
        <div class="lista-canciones">
            <?php 
            mysqli_data_seek($resultado_canciones, 0);
            $numero = 1; 
            ?>
            <?php while ($cancion = mysqli_fetch_assoc($resultado_canciones)): ?>
                <div class="cancion-fila">
                    <span class="cancion-numero"><?php echo $numero; ?></span>
                    <span class="cancion-titulo">
                        <?php echo htmlspecialchars($cancion['titulo']); ?>
                        <?php echo $cancion['es_favorito'] ? ' ⭐' : ''; ?>
                    </span>
                    <span class="cancion-duracion">
                        <?php echo $cancion['duracion'] ? $cancion['duracion'] : '—'; ?>
                    </span>
                </div>
            <?php $numero++; endwhile; ?>
        </div>
    <?php else: ?>
        <p style="margin-top: 30px;">Este álbum no tiene canciones registradas.</p>
    <?php endif; ?>

    <a href="../canciones/agregar.php" class="btn" style="margin-top: 20px;">+ Agregar canción</a>

</body>
</html>