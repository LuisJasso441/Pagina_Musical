<?php
$carpeta_actual = basename(dirname($_SERVER['PHP_SELF']));
$subcarpetas = ['artistas', 'albumes', 'canciones', 'playlists', 'api', 'descubrimientos'];
$base = in_array($carpeta_actual, $subcarpetas) ? '../' : '';

$archivo_actual = basename($_SERVER['PHP_SELF']);
$en_inicio = ($archivo_actual === 'index.php' && !in_array($carpeta_actual, $subcarpetas));
?>
<nav class="navbar">
    <div class="navbar-logo">
        <a href="<?php echo $base; ?>index.php">🎵 Music Chill</a>
    </div>
    <ul class="navbar-links">
        <li>
            <a href="<?php echo $base; ?>index.php" 
               class="<?php echo $en_inicio ? 'activo' : ''; ?>">
                Inicio
            </a>
        </li>
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
        <li>
            <a href="<?php echo $base; ?>descubrimientos/index.php"
            class="<?php echo $carpeta_actual === 'descubrimientos' ? 'activo' : ''; ?>">
                Descubrimientos
            </a>
        </li>
    </ul>
</nav>