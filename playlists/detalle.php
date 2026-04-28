<?php
require '../conexion.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM playlists WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$playlist = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$playlist) {
    header("Location: index.php");
    exit;
}

$sql_canciones = "SELECT canciones.*, albumes.titulo AS album_titulo, artistas.nombre AS artista_nombre 
                  FROM playlist_canciones 
                  JOIN canciones ON playlist_canciones.cancion_id = canciones.id 
                  JOIN albumes ON canciones.album_id = albumes.id 
                  JOIN artistas ON albumes.artista_id = artistas.id 
                  WHERE playlist_canciones.playlist_id = ? 
                  ORDER BY playlist_canciones.orden ASC, playlist_canciones.agregado_en ASC";
$stmt_canciones = mysqli_prepare($conexion, $sql_canciones);
mysqli_stmt_bind_param($stmt_canciones, "i", $id);
mysqli_stmt_execute($stmt_canciones);
$resultado_canciones = mysqli_stmt_get_result($stmt_canciones);
$total = mysqli_num_rows($resultado_canciones);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($playlist['nombre']); ?> - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <a href="index.php" class="volver-link">← Volver a playlists</a>

    <div class="playlist-header">
        <div class="playlist-icono-grande">🎶</div>
        <div>
            <h1 class="detalle-titulo"><?php echo htmlspecialchars($playlist['nombre']); ?></h1>
            <p class="detalle-meta">
                <?php echo $playlist['descripcion'] ? htmlspecialchars($playlist['descripcion']) : ''; ?>
            </p>
            <p class="detalle-meta"><?php echo $total; ?> canciones</p>
        </div>
    </div>

    <?php if (isset($_GET['mensaje'])): ?>
        <?php if ($_GET['mensaje'] === 'cancion_agregada'): ?>
            <p class="mensaje-exito">¡Canción agregada a la playlist!</p>
        <?php elseif ($_GET['mensaje'] === 'cancion_removida'): ?>
            <p class="mensaje-exito" style="color: #ff9800;">Canción removida de la playlist.</p>
        <?php endif; ?>
    <?php endif; ?>

    <div class="agregar-a-playlist">
        <a href="buscar_cancion.php?playlist_id=<?php echo $playlist['id']; ?>" class="btn btn-api">
            🔍 Buscar en Last.fm
        </a>

        <div class="busqueda-local">
            <div class="busqueda-local-campo">
                <input type="text" id="busqueda-input" 
                       placeholder="Buscar en mi colección..." 
                       autocomplete="off">
                <div id="busqueda-resultados" class="busqueda-resultados"></div>
            </div>
        </div>
    </div>

    <script>
    var inputBusqueda = document.getElementById('busqueda-input');
    var contenedorResultados = document.getElementById('busqueda-resultados');
    var playlistId = <?php echo $playlist['id']; ?>;
    var temporizador = null;

    inputBusqueda.addEventListener('input', function() {
        var termino = this.value.trim();

        // Limpiamos el temporizador anterior
        clearTimeout(temporizador);

        // Si el campo está vacío, ocultamos resultados
        if (termino.length < 2) {
            contenedorResultados.innerHTML = '';
            contenedorResultados.style.display = 'none';
            return;
        }

        // Esperamos 300ms después de que el usuario deje de escribir
        temporizador = setTimeout(function() {
            buscarCanciones(termino);
        }, 300);
    });

    function buscarCanciones(termino) {
        var url = 'api_buscar_local.php?playlist_id=' + playlistId + '&q=' + encodeURIComponent(termino);

        fetch(url)
            .then(function(respuesta) {
                return respuesta.json();
            })
            .then(function(canciones) {
                mostrarResultados(canciones);
            });
    }

    function mostrarResultados(canciones) {
        if (canciones.length === 0) {
            contenedorResultados.innerHTML = '<div class="busqueda-vacio">No se encontraron canciones</div>';
            contenedorResultados.style.display = 'block';
            return;
        }

        var html = '';
        for (var i = 0; i < canciones.length; i++) {
            var c = canciones[i];
            html += '<div class="busqueda-item">';
            html += '<div class="busqueda-item-info">';
            html += '<span class="busqueda-item-titulo">' + c.titulo + '</span>';
            html += '<span class="busqueda-item-detalle">' + c.artista + ' · ' + c.album + '</span>';
            html += '</div>';
            if (c.duracion) {
                html += '<span class="cancion-duracion">' + c.duracion + '</span>';
            }
            html += '<button onclick="agregarCancion(' + c.id + ', this)" class="btn btn-pequeno">+</button>';
            html += '</div>';
        }

        contenedorResultados.innerHTML = html;
        contenedorResultados.style.display = 'block';
    }

    function agregarCancion(cancionId, boton) {
        // Deshabilitamos el botón para evitar doble clic
        boton.disabled = true;
        boton.textContent = '...';

        // Enviamos la petición POST con fetch
        var formData = new FormData();
        formData.append('playlist_id', playlistId);
        formData.append('cancion_id', cancionId);

        fetch('agregar_cancion.php', {
            method: 'POST',
            body: formData
        })
        .then(function() {
            // Reemplazamos el botón por una marca de éxito
            boton.textContent = '✓';
            boton.classList.add('btn-agregado');

            // Recargamos la página después de un momento para actualizar la lista
            setTimeout(function() {
                window.location.reload();
            }, 500);
        });
    }

    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.busqueda-local')) {
            contenedorResultados.style.display = 'none';
        }
    });
    </script>

    <?php if ($total > 0): ?>
        <div class="lista-canciones">
            <?php 
            mysqli_data_seek($resultado_canciones, 0);
            $numero = 1;
            while ($cancion = mysqli_fetch_assoc($resultado_canciones)): 
            ?>
                <div class="cancion-fila">
                    <span class="cancion-numero"><?php echo $numero; ?></span>
                    <div class="cancion-info-playlist">
                        <span class="cancion-titulo"><?php echo htmlspecialchars($cancion['titulo']); ?></span>
                        <span class="cancion-artista-album">
                            <?php echo htmlspecialchars($cancion['artista_nombre'] . ' · ' . $cancion['album_titulo']); ?>
                        </span>
                    </div>
                    <span class="cancion-duracion">
                        <?php echo $cancion['duracion'] ? $cancion['duracion'] : '—'; ?>
                    </span>
                    <a href="quitar_cancion.php?playlist_id=<?php echo $playlist['id']; ?>&cancion_id=<?php echo $cancion['id']; ?>"
                       class="btn-quitar"
                       onclick="return confirm('¿Quitar esta canción de la playlist?')">
                        ✕
                    </a>
                </div>
            <?php $numero++; endwhile; ?>
        </div>
    <?php endif; ?>

</body>
</html>