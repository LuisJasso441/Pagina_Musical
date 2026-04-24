<?php
require '../lastfm.php';
require '../conexion.php';

if (!isset($_GET['id']) || !isset($_GET['nombre'])) {
    header("Location: ../artistas/index.php");
    exit;
}

$artista_id = $_GET['id'];
$nombre_artista = $_GET['nombre'];

// Obtenemos álbumes de la API
$albumes_api = lastfm_albumes_artista($nombre_artista, 30);

// Obtenemos los títulos de álbumes que ya tenemos en la BD para este artista
$sql_existentes = "SELECT titulo FROM albumes WHERE artista_id = ?";
$stmt_ex = mysqli_prepare($conexion, $sql_existentes);
mysqli_stmt_bind_param($stmt_ex, "i", $artista_id);
mysqli_stmt_execute($stmt_ex);
$res_ex = mysqli_stmt_get_result($stmt_ex);
$existentes = [];
while ($fila = mysqli_fetch_assoc($res_ex)) {
    $existentes[] = strtolower($fila['titulo']);
}
mysqli_stmt_close($stmt_ex);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Álbumes - <?php echo htmlspecialchars($nombre_artista); ?></title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <a href="../artistas/detalle.php?id=<?php echo $artista_id; ?>" class="volver-link">
        ← Volver a <?php echo htmlspecialchars($nombre_artista); ?>
    </a>

    <h1>Importar Álbumes de <?php echo htmlspecialchars($nombre_artista); ?></h1>
    <p class="detalle-meta">Selecciona los álbumes que quieres agregar a tu colección.</p>

    <?php if (count($albumes_api) > 0): ?>

        <form action="procesar_albumes.php" method="POST">
            <input type="hidden" name="artista_id" value="<?php echo $artista_id; ?>">
            <input type="hidden" name="nombre_artista" value="<?php echo htmlspecialchars($nombre_artista); ?>">

            <div class="album-grid">
                <?php foreach ($albumes_api as $album): ?>
                    <?php 
                    if ($album['name'] === '(null)') continue;

                    $ya_existe = in_array(strtolower($album['name']), $existentes);

                    // Buscamos la imagen
                    $imagen_url = '';
                    if (isset($album['image'])) {
                        foreach ($album['image'] as $img) {
                            if ($img['size'] === 'large') {
                                $imagen_url = $img['#text'];
                            }
                        }
                    }
                    ?>
                    <div class="album-card album-importar <?php echo $ya_existe ? 'album-existente' : ''; ?>">
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
                            <?php if ($ya_existe): ?>
                                <p class="album-anio" style="color: #4caf50;">✓ Ya importado</p>
                            <?php else: ?>
                                <label class="checkbox-importar">
                                    <input type="checkbox" name="albumes[]" 
                                           value="<?php echo htmlspecialchars($album['name']); ?>">
                                    <span>Seleccionar</span>
                                </label>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="barra-importar">
                <button type="submit" class="btn">Importar seleccionados</button>
                <button type="button" class="btn btn-secundario" onclick="seleccionarTodos()">
                    Seleccionar todos
                </button>
            </div>

        </form>

        <script>
        function seleccionarTodos() {
            // Obtenemos todos los checkboxes
            var checkboxes = document.querySelectorAll('input[name="albumes[]"]');

            // Verificamos si todos están marcados
            var todosMarcados = true;
            checkboxes.forEach(function(cb) {
                if (!cb.checked) todosMarcados = false;
            });

            // Si todos están marcados, desmarcamos todos. Si no, marcamos todos
            checkboxes.forEach(function(cb) {
                cb.checked = !todosMarcados;
            });
        }
        </script>

    <?php else: ?>
        <p>No se encontraron álbumes en Last.fm.</p>
    <?php endif; ?>

</body>
</html>