<?php
require '../conexion.php';

$mes_actual = date('Y-m');
$anio_actual = date('Y');

// Descubrimientos del mes
$sql_mes = "SELECT * FROM descubrimientos WHERE tipo = 'mes' AND periodo = ? ORDER BY categoria, creado_en DESC";
$stmt_mes = mysqli_prepare($conexion, $sql_mes);
mysqli_stmt_bind_param($stmt_mes, "s", $mes_actual);
mysqli_stmt_execute($stmt_mes);
$resultado_mes = mysqli_stmt_get_result($stmt_mes);

$desc_mes = ['cancion' => [], 'artista' => [], 'album' => []];
while ($fila = mysqli_fetch_assoc($resultado_mes)) {
    $desc_mes[$fila['categoria']][] = $fila;
}
mysqli_stmt_close($stmt_mes);

// Descubrimientos del año
$sql_anio = "SELECT * FROM descubrimientos WHERE tipo = 'anio' AND periodo = ? ORDER BY categoria, creado_en DESC";
$stmt_anio = mysqli_prepare($conexion, $sql_anio);
mysqli_stmt_bind_param($stmt_anio, "s", $anio_actual);
mysqli_stmt_execute($stmt_anio);
$resultado_anio = mysqli_stmt_get_result($stmt_anio);

$desc_anio = ['cancion' => [], 'artista' => [], 'album' => []];
while ($fila = mysqli_fetch_assoc($resultado_anio)) {
    $desc_anio[$fila['categoria']][] = $fila;
}
mysqli_stmt_close($stmt_anio);

// Nombres legibles para las categorías
$nombres_categoria = [
    'cancion' => '🎵 Canciones',
    'artista' => '🎤 Artistas',
    'album'   => '💿 Álbumes'
];

// Nombre del mes en español
$meses_esp = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$num_mes = (int)date('m');
$nombre_mes = $meses_esp[$num_mes];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descubrimientos - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Mis Descubrimientos</h1>

    <a href="agregar.php" class="btn" style="margin-bottom: 30px;">+ Agregar descubrimiento</a>

    <!-- DESCUBRIMIENTO DEL MES -->
    <div class="descubrimiento-seccion">
        <div class="descubrimiento-header descubrimiento-mes">
            <h2>🌟 Descubrimiento del Mes</h2>
            <span class="descubrimiento-periodo"><?php echo $nombre_mes . ' ' . $anio_actual; ?></span>
        </div>

        <div class="descubrimiento-grid">
            <?php foreach ($nombres_categoria as $clave => $nombre): ?>
                <div class="descubrimiento-categoria">
                    <h3><?php echo $nombre; ?></h3>

                    <?php if (count($desc_mes[$clave]) > 0): ?>
                        <?php foreach ($desc_mes[$clave] as $item): ?>
                            <div class="descubrimiento-item">
                                <div class="descubrimiento-item-info">
                                    <strong><?php echo htmlspecialchars($item['titulo']); ?></strong>
                                    <?php if (!empty($item['artista'])): ?>
                                        <span class="descubrimiento-artista">de <?php echo htmlspecialchars($item['artista']); ?></span>
                                    <?php endif; ?>
                                    <?php if ($item['descripcion']): ?>
                                        <p class="descubrimiento-desc"><?php echo htmlspecialchars($item['descripcion']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <a href="eliminar.php?id=<?php echo $item['id']; ?>" 
                                   class="btn-eliminar-mini"
                                   onclick="return confirm('¿Eliminar este descubrimiento?');">✕</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="descubrimiento-vacio">Aún no hay descubrimientos</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- DESCUBRIMIENTO DEL AÑO -->
    <div class="descubrimiento-seccion">
        <div class="descubrimiento-header descubrimiento-anio">
            <h2>🏆 Descubrimiento del Año</h2>
            <span class="descubrimiento-periodo"><?php echo $anio_actual; ?></span>
        </div>

        <div class="descubrimiento-grid">
            <?php foreach ($nombres_categoria as $clave => $nombre): ?>
                <div class="descubrimiento-categoria">
                    <h3><?php echo $nombre; ?></h3>

                    <?php if (count($desc_anio[$clave]) > 0): ?>
                        <?php foreach ($desc_anio[$clave] as $item): ?>
                            <div class="descubrimiento-item">
                                <div class="descubrimiento-item-info">
                                    <strong><?php echo htmlspecialchars($item['titulo']); ?></strong>
                                    <?php if (!empty($item['artista'])): ?>
                                        <span class="descubrimiento-artista">de <?php echo htmlspecialchars($item['artista']); ?></span>
                                    <?php endif; ?>
                                    <?php if ($item['descripcion']): ?>
                                        <p class="descubrimiento-desc"><?php echo htmlspecialchars($item['descripcion']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <a href="eliminar.php?id=<?php echo $item['id']; ?>" 
                                   class="btn-eliminar-mini"
                                   onclick="return confirm('¿Eliminar este descubrimiento?');">✕</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="descubrimiento-vacio">Aún no hay descubrimientos</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>