<nav class="navbar">
    <div class="navbar-logo">
        <a href="index.php">🎵 Mi Música</a>
    </div>
    <ul class="navbar-links">
        <li>
            <a href="index.php" 
               class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'activo' : ''; ?>">
                Artistas
            </a>
        </li>
        <li>
            <a href="albumes.php" 
               class="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['albumes.php', 'detalle_album.php']) ? 'activo' : ''; ?>">
                Álbumes
            </a>
        </li>
        <li>
            <a href="canciones.php" 
               class="<?php echo basename($_SERVER['PHP_SELF']) === 'canciones.php' ? 'activo' : ''; ?>">
                Canciones
            </a>
        </li>
    </ul>
</nav>