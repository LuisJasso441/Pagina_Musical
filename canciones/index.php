<?php
require '../conexion.php';

// Leemos el modo de vista
$vista = isset($_GET['vista']) ? $_GET['vista'] : 'general';

// Canciones favoritas (para vista general)
$sql_favoritas = "SELECT canciones.*, albumes.titulo AS album_titulo, 
                         artistas.nombre AS artista_nombre, albumes.imagen AS album_imagen
                  FROM canciones 
                  JOIN albumes ON canciones.album_id = albumes.id 
                  JOIN artistas ON albumes.artista_id = artistas.id 
                  WHERE canciones.es_favorito = 1 
                  ORDER BY artistas.nombre ASC, albumes.titulo ASC, canciones.titulo ASC";
$resultado_favoritas = mysqli_query($conexion, $sql_favoritas);
$total_favoritas = mysqli_num_rows($resultado_favoritas);

// Artistas que tienen canciones favoritas (para vista por artista)
$sql_artistas = "SELECT artistas.id, artistas.nombre, artistas.genero,
                        COUNT(canciones.id) AS total_favs
                 FROM artistas 
                 JOIN albumes ON artistas.id = albumes.artista_id 
                 JOIN canciones ON albumes.id = canciones.album_id 
                 WHERE canciones.es_favorito = 1 
                 GROUP BY artistas.id 
                 ORDER BY artistas.nombre ASC";
$resultado_artistas = mysqli_query($conexion, $sql_artistas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Música - Canciones Favoritas</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>⭐ Mis Favoritas</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <?php if ($_GET['mensaje'] === 'cancion_actualizada'): ?>
            <p class="mensaje-exito">¡Canción actualizada exitosamente!</p>
        <?php endif; ?>
    <?php endif; ?>

    <!-- SELECTOR DE VISTA -->
    <div class="filtros-barra">
        <div class="filtros-grupo">
            <a href="index.php?vista=general" 
               class="filtro-btn <?php echo $vista === 'general' ? 'filtro-activo' : ''; ?>">
                Todas las favoritas
            </a>
            <a href="index.php?vista=artista" 
               class="filtro-btn <?php echo $vista === 'artista' ? 'filtro-activo' : ''; ?>">
                Por artista
            </a>
        </div>
        <span class="detalle-meta"><?php echo $total_favoritas; ?> canciones favoritas</span>
    </div>

    <?php if ($total_favoritas === 0): ?>
        <p>No tienes canciones favoritas todavía. Edita una canción y márcala como favorita ⭐</p>

    <?php elseif ($vista === 'general'): ?>
        <!-- ==================== VISTA GENERAL ==================== -->
        <div class="lista-canciones">
            <?php 
            $artista_actual = '';
            $numero = 1;
            while ($cancion = mysqli_fetch_assoc($resultado_favoritas)): 
            ?>
                <?php if ($cancion['artista_nombre'] !== $artista_actual): ?>
                    <?php $artista_actual = $cancion['artista_nombre']; ?>
                    <div class="separador-album">
                        <?php echo htmlspecialchars($artista_actual); ?>
                    </div>
                <?php endif; ?>

                <div class="cancion-fila">
                    <span class="cancion-numero"><?php echo $numero; ?></span>
                    <div class="cancion-info-playlist">
                        <span class="cancion-titulo">
                            <?php echo htmlspecialchars($cancion['titulo']); ?> ⭐
                        </span>
                        <span class="cancion-artista-album">
                            <?php echo htmlspecialchars($cancion['album_titulo']); ?>
                        </span>
                    </div>
                    <span class="cancion-duracion">
                        <?php echo $cancion['duracion'] ? $cancion['duracion'] : '—'; ?>
                    </span>
                </div>
            <?php $numero++; endwhile; ?>
        </div>

    <?php else: ?>
        <!-- ==================== VISTA POR ARTISTA ==================== -->
        <div class="artistas-favs-grid">
            <?php while ($artista = mysqli_fetch_assoc($resultado_artistas)): ?>
                <div class="artista-fav-card" onclick="abrirModal(<?php echo $artista['id']; ?>)">
                    <div class="artista-fav-avatar">
                        <?php echo mb_strtoupper(mb_substr($artista['nombre'], 0, 1)); ?>
                    </div>
                    <div class="artista-fav-info">
                        <h3><?php echo htmlspecialchars($artista['nombre']); ?></h3>
                        <p><?php echo htmlspecialchars($artista['genero']); ?></p>
                        <span class="artista-fav-count"><?php echo $artista['total_favs']; ?> ⭐</span>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- ==================== MODAL ==================== -->
        <div id="modal-overlay" class="modal-overlay" onclick="cerrarModal(event)">
            <div class="modal-contenido">
                <button class="modal-cerrar" onclick="cerrarModal(event)">✕</button>
                <div id="modal-header" class="modal-header">
                    <div class="artista-avatar modal-avatar"></div>
                    <div>
                        <h2 id="modal-titulo"></h2>
                        <p id="modal-subtitulo" class="detalle-meta"></p>
                    </div>
                </div>
                <div id="modal-canciones" class="lista-canciones">
                    <p>Cargando...</p>
                </div>
            </div>
        </div>

        <?php
        // Preparamos los datos de canciones favoritas por artista en formato JSON
        // para que JavaScript pueda usarlos sin hacer otra petición al servidor
        $sql_todas = "SELECT canciones.titulo, canciones.duracion, 
                             albumes.titulo AS album_titulo, artistas.id AS artista_id
                      FROM canciones 
                      JOIN albumes ON canciones.album_id = albumes.id 
                      JOIN artistas ON albumes.artista_id = artistas.id 
                      WHERE canciones.es_favorito = 1 
                      ORDER BY albumes.titulo ASC, canciones.titulo ASC";
        $res_todas = mysqli_query($conexion, $sql_todas);

        $datos_por_artista = [];
        while ($fila = mysqli_fetch_assoc($res_todas)) {
            $aid = $fila['artista_id'];
            if (!isset($datos_por_artista[$aid])) {
                $datos_por_artista[$aid] = [];
            }
            $datos_por_artista[$aid][] = [
                'titulo' => $fila['titulo'],
                'duracion' => $fila['duracion'] ? $fila['duracion'] : '—',
                'album' => $fila['album_titulo']
            ];
        }

        // También guardamos los nombres de artistas
        mysqli_data_seek($resultado_artistas, 0);
        $nombres_artistas = [];
        while ($a = mysqli_fetch_assoc($resultado_artistas)) {
            $nombres_artistas[$a['id']] = [
                'nombre' => $a['nombre'],
                'genero' => $a['genero'],
                'total' => $a['total_favs']
            ];
        }
        ?>

        <script>
        // Datos que PHP generó para nosotros
        var cancionesPorArtista = <?php echo json_encode($datos_por_artista); ?>;
        var nombresArtistas = <?php echo json_encode($nombres_artistas); ?>;

        function abrirModal(artistaId) {
            var overlay = document.getElementById('modal-overlay');
            var titulo = document.getElementById('modal-titulo');
            var subtitulo = document.getElementById('modal-subtitulo');
            var contenedor = document.getElementById('modal-canciones');
            var avatar = document.querySelector('.modal-avatar');

            // Llenamos el header del modal
            var artista = nombresArtistas[artistaId];
            titulo.textContent = artista.nombre;
            subtitulo.textContent = artista.genero + ' · ' + artista.total + ' favoritas';
            avatar.textContent = artista.nombre.charAt(0).toUpperCase();

            // Construimos la lista de canciones
            var canciones = cancionesPorArtista[artistaId];
            var html = '';

            if (canciones && canciones.length > 0) {
                for (var i = 0; i < canciones.length; i++) {
                    html += '<div class="cancion-fila">';
                    html += '<span class="cancion-numero">' + (i + 1) + '</span>';
                    html += '<div class="cancion-info-playlist">';
                    html += '<span class="cancion-titulo">' + canciones[i].titulo + ' ⭐</span>';
                    html += '<span class="cancion-artista-album">' + canciones[i].album + '</span>';
                    html += '</div>';
                    html += '<span class="cancion-duracion">' + canciones[i].duracion + '</span>';
                    html += '</div>';
                }
            }

            contenedor.innerHTML = html;

            // Mostramos el modal
            overlay.classList.add('modal-visible');

            // Bloqueamos el scroll del body
            document.body.style.overflow = 'hidden';
        }

        function cerrarModal(event) {
            // Solo cerramos si hicieron clic en el overlay o en el botón de cerrar
            if (event.target.id === 'modal-overlay' || event.target.classList.contains('modal-cerrar')) {
                var overlay = document.getElementById('modal-overlay');
                overlay.classList.remove('modal-visible');
                document.body.style.overflow = '';
            }
        }

        // Cerrar con la tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var overlay = document.getElementById('modal-overlay');
                overlay.classList.remove('modal-visible');
                document.body.style.overflow = '';
            }
        });
        </script>

    <?php endif; ?>

</body>
</html>