<?php
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../Backend/controllers/UsuarioControlador.php';

    $controlador = new UsuarioControlador();

    $datos = [
        'email' => $_POST['email'] ?? '',
        'password_hash' => $_POST['password'] ?? ''
    ];

    if ($controlador->login($datos)) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Credenciales incorrectas o usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventosC | Acceso</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Segoe+UI:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="Frontend/assets/css/login.css">
    <link rel="stylesheet" href="Frontend/assets/css/footer.css">
    <!--<link rel="stylesheet" href="Frontend/assets/css/index.css">-->

</head>

<body>

    <div class="video-bg-container">
        <video autoplay muted loop class="back-video">
            <source src="Frontend/assets/img/background.mp4" type="video/mp4">
        </video>
        <div class="overlay-dark"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-left">
                <div class="login-header">
                    <h2 class="cy-title">INICIAR <span class="neon-text">SESIÓN</span></h2>
                    <p>Accede a tu cuenta.</p>
                </div>

                <form id="loginForm" action="index.php?view=login" method="POST">

                    <?php if (!empty($error)): ?>
                        <div
                            style="background: #ff4d4d20; border: 1px solid #ff4d4d; color: #ff4d4d; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.9em;">
                            ⚠️ <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="input-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="text" id="email" name="email" placeholder="ejemplo@eventosc.com" autocomplete="off"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <small class="error-msg">Ingresa un correo válido</small>
                    </div>

                    <div class="input-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" placeholder="••••••••">
                        <small class="error-msg">La contraseña es requerida</small>
                    </div>

                    <div class="form-actions"
                        style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                        <a href="index.php?view=register" class="forgot-pass"
                            style="color: #fff; opacity: 0.8; font-size: 0.85em;">
                            ¿No tienes cuenta? <span style="color: #39ff14; font-weight: bold;">Regístrate</span>
                        </a>
                        <a href="#" class="forgot-pass" style="color: #39ff14; font-weight: 700; font-size: 0.85em;">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button type="submit" class="btn-login" style="margin-top: 20px;">
                        ACCEDER
                    </button>

                    <div class="back-link">
                        <a href="index.php">← Volver al inicio</a>
                    </div>
                </form>
            </div>

            <div class="login-right">
                <img src="Frontend/assets/img/logoeventoscsiglas.png" alt="EventosC Logo" class="ec-logo-glow">
                <div class="right-text">
                    <h3>GESTIÓN INTEGRAL</h3>
                    <p>Plataforma v.2.0.25</p>
                </div>
            </div>

        </div>
    </div>

    <?php require_once 'Frontend/views/layouts/footer.php'; ?>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            let valid = true;
            const email = document.getElementById('email');
            const pass = document.getElementById('password');

            [email, pass].forEach(input => input.parentElement.classList.remove('error'));

            if (!email.value.includes('@') || email.value.length < 5) {
                email.parentElement.classList.add('error');
                valid = false;
            }
            if (pass.value.length < 1) {
                pass.parentElement.classList.add('error');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
                const card = document.querySelector('.login-card');
                card.classList.add('shake');
                setTimeout(() => card.classList.remove('shake'), 500);
            }
        });
    </script>

</body>

</html>