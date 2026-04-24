<?php
require '../conexion.php';

$sql = "SELECT * FROM artistas ORDER BY nombre ASC";
$resultado = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Música - Artistas</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Mis Artistas</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <?php if ($_GET['mensaje'] === 'artista_guardado'): ?>
            <p class="mensaje-exito">¡Artista guardado exitosamente!</p>
        <?php elseif ($_GET['mensaje'] === 'artista_actualizado'): ?>
            <p class="mensaje-exito">¡Artista actualizado exitosamente!</p>
        <?php elseif ($_GET['mensaje'] === 'artista_eliminado'): ?>
            <p class="mensaje-exito" style="color: #ff9800;">Artista eliminado.</p>
        <?php elseif ($_GET['mensaje'] === 'artista_tiene_albumes'): ?>
            <p class="mensaje-error">No se puede eliminar: este artista tiene álbumes asociados.</p>
        <?php endif; ?>
    <?php endif; ?>

    <a href="agregar.php" class="btn">+ Agregar nuevo artista</a>
    <a href="agregar.php" class="btn">+ Agregar manualmente</a>
    <a href="../api/buscar_artista.php" class="btn btn-api" style="margin-left: 10px;">Buscar en Last.fm</a>

    <?php if (mysqli_num_rows($resultado) > 0): ?>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Género</th>
                    <th>Favorito</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($artista = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td>
                            <a href="detalle.php?id=<?php echo $artista['id']; ?>" class="enlace-nombre">
                                <?php echo htmlspecialchars($artista['nombre']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($artista['genero']); ?></td>
                        <td><?php echo $artista['es_favorito'] ? '⭐' : '—'; ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $artista['id']; ?>">Editar</a>
                            |
                            <a href="eliminar.php?id=<?php echo $artista['id']; ?>"
                               onclick="return confirm('¿Estás seguro de eliminar este artista?')">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>No hay artistas registrados todavía.</p>
    <?php endif; ?>

</body>
</html>