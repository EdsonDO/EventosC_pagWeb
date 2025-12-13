<?php
$registroExitoso = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../Backend/controllers/UsuarioControlador.php';
    $controlador = new UsuarioControlador();

    $datos = [
        'nombre_completo' => $_POST['name'],
        'email' => $_POST['email'],
        'password_hash' => $_POST['password']
    ];

    if ($controlador->register($datos)) {
        $registroExitoso = true;
    } else {
        $error = "No se pudo registrar el usuario. El correo podría estar en uso.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventosC | Registro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Segoe+UI:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="Frontend/assets/css/register.css">
    <!--<link rel="stylesheet" href="Frontend/assets/css/index.css">-->

</head>

<body>

    <div class="video-bg-container">
        <video autoplay muted loop class="back-video">
            <source src="Frontend/assets/img/background.mp4" type="video/mp4">
        </video>
        <div class="overlay-dark"></div>
    </div>

    <div id="successModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-icon">✅</div>
            <h3 class="modal-title">¡Cuenta Creada!</h3>
            <p class="modal-text">Tu registro en EventosC ha sido exitoso. Ya puedes acceder a la plataforma.</p>
            <a href="index.php?view=login" class="btn-modal">
                IR AL LOGIN AHORA
            </a>
        </div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-left">
                <div class="login-header">
                    <h2 class="cy-title">CREAR <span class="neon-text">CUENTA</span></h2>
                    <p>Únete a la plataforma de gestión definitiva.</p>
                </div>

                <form id="registerForm" action="" method="POST">

                    <?php if (!empty($error)): ?>
                        <div
                            style="background: #ff4d4d20; border: 1px solid #ff4d4d; color: #ff4d4d; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                            ⚠️ <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="input-group">
                        <label for="name">Nombre Completo</label>
                        <input type="text" id="name" name="name" placeholder="Tu Nombre y Apellido" autocomplete="off"
                            required>
                    </div>

                    <div class="input-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" placeholder="ejemplo@eventosc.com"
                            autocomplete="off" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="input-group">
                        <label for="confirm_password">Repetir Contraseña</label>
                        <input type="password" id="confirm_password" placeholder="••••••••" required>
                        <small class="error-msg">Las contraseñas no coinciden</small>
                    </div>

                    <button type="submit" class="btn-login">
                        REGISTRARSE
                    </button>

                    <div class="back-link">
                        <a href="index.php?view=login">← ¿Ya tienes cuenta? Inicia Sesión</a>
                    </div>
                </form>
            </div>

            <div class="login-right">
                <img src="Frontend/assets/img/logoeventoscsiglas.png" alt="EventosC Logo" class="ec-logo-glow">
                <div class="right-text">
                    <h3>UNIVERSE</h3>
                    <p>Acceso a todas las herramientas</p>
                </div>
            </div>

        </div>
    </div>
    <?php require_once 'Frontend/views/layouts/footer.php'; ?>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            let valid = true;
            const pass = document.getElementById('password');
            const confirm = document.getElementById('confirm_password');

            confirm.parentElement.classList.remove('error');

            if (pass.value !== confirm.value) {
                confirm.parentElement.classList.add('error');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
                const card = document.querySelector('.login-card');
                card.classList.add('shake');
                setTimeout(() => card.classList.remove('shake'), 500);
            }
        });

        <?php if ($registroExitoso): ?>
            const modal = document.getElementById('successModal');
            modal.classList.add('active');

        <?php endif; ?>
    </script>

</body>

</html>