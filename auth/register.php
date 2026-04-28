<?php
session_start();
require '../conexion.php';

if (isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';
$exito = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmar = $_POST['confirmar_password'];
    $genero = $_POST['genero_favorito'];

    if (empty($username) || empty($password) || empty($confirmar)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif (strlen($username) < 3) {
        $error = 'El nombre de usuario debe tener al menos 3 caracteres.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $confirmar) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        // Verificamos si el username ya existe
        $sql = "SELECT id FROM usuarios WHERE username = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        if (mysqli_fetch_assoc($resultado)) {
            $error = 'Ese nombre de usuario ya está en uso.';
        } else {
            // Hasheamos la contraseña
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (username, password, genero_favorito) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $username, $password_hash, $genero);

            if (mysqli_stmt_execute($stmt)) {
                $exito = true;
            } else {
                $error = 'Error al registrar. Intenta de nuevo.';
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Music Chill</title>
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
        <p class="auth-subtitulo">Crea tu cuenta</p>

        <?php if ($exito): ?>
            <div class="auth-exito">
                ¡Registro exitoso! <a href="login.php">Inicia sesión aquí</a>
            </div>
        <?php else: ?>

            <?php if ($error): ?>
                <div class="auth-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" id="username" name="username" 
                           placeholder="Elige un nombre de usuario" required minlength="3"
                           value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Mínimo 6 caracteres" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="confirmar_password">Confirmar contraseña</label>
                    <input type="password" id="confirmar_password" name="confirmar_password" 
                           placeholder="Repite tu contraseña" required>
                </div>
                <div class="form-group">
                    <label for="genero_favorito">Género musical favorito</label>
                    <select id="genero_favorito" name="genero_favorito" required>
                        <option value="">-- Selecciona tu género --</option>
                        <?php
                        $generos = [
                            'Alternativa', 'Bachata', 'Banda', 'Blues', 'Clásica',
                            'Corridos', 'Country', 'Cumbia', 'Dance', 'Dubstep',
                            'Electrónica', 'Folk', 'Funk', 'Gospel', 'Hip-Hop',
                            'House', 'Indie', 'Jazz', 'Latino', 'Merengue',
                            'Metal', 'Norteño', 'Ópera', 'Pop', 'Punk',
                            'R&B', 'Rap', 'Reggae', 'Reggaetón', 'Regional Mexicano',
                            'Rock', 'Salsa', 'Ska', 'Soul', 'Techno', 'Trap'
                        ];
                        foreach ($generos as $g):
                            $selected = (isset($genero) && $genero === $g) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $g; ?>" <?php echo $selected; ?>>
                                <?php echo $g; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Registrarse</button>
            </form>

        <?php endif; ?>

        <div class="auth-link">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
        </div>

        <div class="auth-footer">© 2026 Music Chill</div>
    </div>

    <script>
    var starsContainer = document.getElementById('stars-container');
    for (var i = 0; i < 150; i++) {
        var star = document.createElement('div');
        star.classList.add('star');
        star.style.top = Math.random() * 100 + '%';
        star.style.left = Math.random() * 100 + '%';
        star.style.animationDuration = (Math.random() * 5 + 2) + 's';
        starsContainer.appendChild(star);
    }

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