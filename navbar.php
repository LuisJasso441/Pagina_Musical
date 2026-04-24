<?php
// Calculamos la ruta base del proyecto
// Si estamos en una subcarpeta (artistas/, albumes/, etc.), la base es "../"
// Si estamos en la raíz, la base es ""
$carpeta_actual = basename(dirname($_SERVER['PHP_SELF']));
$subcarpetas = ['artistas', 'albumes', 'canciones', 'playlists'];
$base = in_array($carpeta_actual, $subcarpetas) ? '../' : '';

// Obtenemos la carpeta y archivo actual para resaltar el enlace activo
$archivo_actual = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <div class="navbar-logo">
        <a href="<?php echo $base; ?>artistas/index.php">🎵 Mi Música</a>
    </div>
    <ul class="navbar-links">
        <li>
            <a href="<?php echo $base; ?>artistas/index.php" 
               class="<?php echo $carpeta_actual === 'artistas' ? 'activo' : ''; ?>">
                Artistas
            </a>
        </li>
        <li>
            <a href="<?php echo $base; ?>albumes/index.php" 
               class="<?php echo $carpeta_actual === 'albumes' ? 'activo' : ''; ?>">
                Álbumes
            </a>
        </li>
        <li>
            <a href="<?php echo $base; ?>canciones/index.php" 
               class="<?php echo $carpeta_actual === 'canciones' ? 'activo' : ''; ?>">
                Canciones
            </a>
        </li>
        <li>
            <a href="<?php echo $base; ?>playlists/index.php" 
               class="<?php echo $carpeta_actual === 'playlists' ? 'activo' : ''; ?>">
                Playlists
            </a>
        </li>
    </ul>
</nav>