<?php
session_start();
require '../conexion.php';

// Si ya está logueado, redirigir al inicio
if (isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        // Buscamos el usuario
        $sql = "SELECT * FROM usuarios WHERE username = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($stmt);

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Contraseña correcta: creamos la sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['genero_favorito'] = $usuario['genero_favorito'];
            header("Location: ../index.php");
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Music Chill</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>

    <div class="background-stars" id="stars-container"></div>

    <div class="background-shapes">
        <div id="shapes-container"></div>
    </div>

    <div class="auth-container">
        <div class="auth-logo">🎵</div>
        <h2>Music Chill</h2>
        <p class="auth-subtitulo">Inicia sesión para continuar</p>

        <?php if ($error): ?>
            <div class="auth-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" 
                       placeholder="Tu nombre de usuario" required
                       value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" 
                       placeholder="Tu contraseña" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>

        <div class="auth-link">
            ¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>
        </div>

        <div class="auth-footer">© 2026 Music Chill</div>
    </div>

    <script>
    // Estrellas de fondo
    var starsContainer = document.getElementById('stars-container');
    for (var i = 0; i < 150; i++) {
        var star = document.createElement('div');
        star.classList.add('star');
        star.style.top = Math.random() * 100 + '%';
        star.style.left = Math.random() * 100 + '%';
        star.style.animationDuration = (Math.random() * 5 + 2) + 's';
        starsContainer.appendChild(star);
    }

    // Figuras flotantes
    var shapesData = [
        { type: 'circle' },
        { type: 'square' },
        { type: 'triangle' }
    ];
    var shapeElements = [];
    var shapesContainer = document.getElementById('shapes-container');

    for (var i = 0; i < 9; i++) {
        var type = shapesData[i % 3].type;
        var el = document.createElement('div');
        el.classList.add('shape', type);
        el.style.top = Math.random() * 100 + 'vh';
        el.style.left = Math.random() * 100 + 'vw';
        shapesContainer.appendChild(el);
        shapeElements.push({
            el: el,
            dx: (Math.random() * 2 + 0.5) * (Math.random() > 0.5 ? 1 : -1),
            dy: (Math.random() * 2 + 0.5) * (Math.random() > 0.5 ? 1 : -1)
        });
    }

    function animateShapes() {
        shapeElements.forEach(function(shape) {
            var rect = shape.el.getBoundingClientRect();
            if (rect.left <= 0 || rect.left + rect.width >= window.innerWidth) shape.dx *= -1;
            if (rect.top <= 0 || rect.top + rect.height >= window.innerHeight) shape.dy *= -1;
            shape.el.style.left = (shape.el.offsetLeft + shape.dx) + 'px';
            shape.el.style.top = (shape.el.offsetTop + shape.dy) + 'px';
        });
        requestAnimationFrame(animateShapes);
    }
    animateShapes();
    </script>

</body>
</html>