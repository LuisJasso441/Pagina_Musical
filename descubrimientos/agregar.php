<?php
$meses_esp = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Descubrimiento - Mi Música</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <?php include '../navbar.php'; ?>

    <h1>Agregar Descubrimiento</h1>

    <a href="index.php" class="volver-link">← Volver a descubrimientos</a>

    <form action="guardar.php" method="POST" style="margin-top: 20px;">

        <label for="tipo">¿Es descubrimiento del mes o del año?</label>
        <select id="tipo" name="tipo" required onchange="togglePeriodo()">
            <option value="mes">Del mes</option>
            <option value="anio">Del año</option>
        </select>

        <div id="campo-mes">
            <label for="mes">¿De qué mes?</label>
            <select id="mes" name="mes">
                <?php foreach ($meses_esp as $num => $nombre): ?>
                    <option value="<?php echo $num; ?>" 
                            <?php echo $num == date('n') ? 'selected' : ''; ?>>
                        <?php echo $nombre; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <label for="anio">¿De qué año?</label>
        <input type="number" id="anio" name="anio" 
               value="<?php echo date('Y'); ?>" min="2000" max="2099" required>

        <label for="categoria">Categoría:</label>
        <select id="categoria" name="categoria" required onchange="toggleArtista()">
            <option value="cancion">🎵 Canción</option>
            <option value="artista">🎤 Artista</option>
            <option value="album">💿 Álbum</option>
        </select>

        <label for="titulo">Nombre:</label>
        <input type="text" id="titulo" name="titulo" 
               placeholder="Ej: Tame Impala, OK Computer, Let It Happen..." required>

        <div id="campo-artista">
            <label for="artista">Artista / Banda:</label>
            <input type="text" id="artista" name="artista" 
                   placeholder="Ej: Radiohead, Daft Punk...">
        </div>

        <label for="descripcion">¿Por qué es tu descubrimiento? (opcional)</label>
        <textarea id="descripcion" name="descripcion" rows="3" 
                  placeholder="Ej: Lo escuché en un playlist random y no paré de repetirlo..."></textarea>

        <button type="submit" class="btn">Guardar descubrimiento</button>
    </form>

    <script>
    function togglePeriodo() {
        var tipo = document.getElementById('tipo').value;
        var campoMes = document.getElementById('campo-mes');
        campoMes.style.display = (tipo === 'mes') ? 'block' : 'none';
    }

    function toggleArtista() {
        var categoria = document.getElementById('categoria').value;
        var campoArtista = document.getElementById('campo-artista');
        campoArtista.style.display = (categoria === 'artista') ? 'none' : 'block';
    }

    // Ejecutar al cargar la página para que el estado inicial sea correcto
    toggleArtista();
    </script>

</body>
</html>