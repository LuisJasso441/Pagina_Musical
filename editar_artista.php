<?php
// Paso 1: Conexión
require 'conexion.php';

// Paso 2: Verificamos que llegó un ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

// Paso 3: Buscamos el artista en la base de datos
$id = $_GET['id'];
$sql = "SELECT * FROM artistas WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// Paso 4: Obtenemos el resultado
$resultado = mysqli_stmt_get_result($stmt);
$artista = mysqli_fetch_assoc($resultado);

// Paso 5: Si no se encontró el artista, regresamos
if (!$artista) {
    header("Location: index.php");
    exit;
}

mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artista - Mi Música</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <h1>Editar Artista</h1>

    <form action="actualizar_artista.php" method="POST">

        <input type="hidden" name="id" value="<?php echo $artista['id']; ?>">

        <label for="nombre">Nombre del artista:</label>
        <input type="text" id="nombre" name="nombre"
               value="<?php echo htmlspecialchars($artista['nombre']); ?>" required>

        <label for="genero">Género musical:</label>
        <input type="text" id="genero" name="genero"
               value="<?php echo htmlspecialchars($artista['genero']); ?>">

        <label for="es_favorito">¿Es favorito?</label>
        <select id="es_favorito" name="es_favorito">
            <option value="0" <?php echo $artista['es_favorito'] == 0 ? 'selected' : ''; ?>>
                No
            </option>
            <option value="1" <?php echo $artista['es_favorito'] == 1 ? 'selected' : ''; ?>>
                Sí
            </option>
        </select>

        <button type="submit">Guardar Cambios</button>

    </form>

    <a href="index.php">← Volver a la lista</a>

</body>
</html>