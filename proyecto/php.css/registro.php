<?php 
include("conexion.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CAPTURA Y LIMPIEZA
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $email  = mysqli_real_escape_string($conexion, $_POST['email']);
    $passPlano = $_POST['pass'];
    $confirmPass = $_POST['confirm_pass'];

    if ($passPlano !== $confirmPass) {
        echo "<script>
                alert('Las contraseñas no coinciden.');
                window.history.back();
              </script>";
        exit();
    }

    if (!isset($_POST['terminos'])) {
        echo "<script>
                alert('Debes aceptar los términos y condiciones.');
                window.history.back();
              </script>";
        exit();
    }

    $password = password_hash($passPlano, PASSWORD_DEFAULT);

     // 1. PRIMERO: Verificamos si el email ya existe en la tabla
    $checkEmail = "SELECT email FROM usuario WHERE email = '$email'";
    $resultadoCheck = mysqli_query($conexion, $checkEmail);

    if (mysqli_num_rows($resultadoCheck) > 0) {
        // Si el conteo es mayor a 0, el correo ya está en la base de datos
        echo "<script>
                alert('El usuario ya está guardado, por favor prueba con otro correo.');
                window.history.back(); // Esto los vuelve al formulario
              </script>";
    } else {
        // 2. SEGUNDO: Si no existe, lo insertamos normalmente
        $sql = "INSERT INTO usuario (nombre, email, pass) VALUES ('$nombre', '$email', '$password')"; 
        
        if (mysqli_query($conexion, $sql)) {
            echo "<script>
                    alert('¡Registro exitoso!');
                    window.location='login.php';
                  </script>";
            exit();
        } else {
            echo "Error inesperado: " . mysqli_error($conexion);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Tuortox</title>
    <link rel="stylesheet" href="estilos.css">

</head>

<body class="auth-container register-page">
    <div class="auth-card">
        <div class="auth-logo" aria-label="Logo de Tuortox">T</div>
        <h1>REGISTRO</h1>
        <p class="auth-subtitle">Crea tu cuenta para comenzar</p>

        <form method="POST">
            <label for="nombre">Nombre completo</label>
            <div class="input-wrapper">
                <span class="input-icon" aria-hidden="true">👤</span>
                <input id="nombre" type="text" name="nombre" placeholder="Nombre completo" required>
            </div>

            <label for="email">Correo electrónico</label>
            <div class="input-wrapper">
                <span class="input-icon" aria-hidden="true">📧</span>
                <input id="email" type="email" name="email" placeholder="Correo electrónico" required>
            </div>

            <label for="pass">Contraseña</label>
            <div class="input-wrapper password-wrapper">
                <span class="input-icon" aria-hidden="true">🔒</span>
                <input id="pass" type="password" name="pass" placeholder="Contraseña" required>
                <button class="password-toggle" type="button" aria-label="Mostrar contraseña" data-target="pass">👁</button>
            </div>

            <div class="password-strength" id="passwordStrength" data-score="0" aria-live="polite">
                <div class="strength-dots" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <small id="strengthText">Ingresa una contraseña</small>
            </div>

            <label for="confirm_pass">Confirmar contraseña</label>
            <div class="input-wrapper password-wrapper">
                <span class="input-icon" aria-hidden="true">🔒</span>
                <input id="confirm_pass" type="password" name="confirm_pass" placeholder="Confirmar contraseña" required>
                <button class="password-toggle" type="button" aria-label="Mostrar contraseña" data-target="confirm_pass">👁</button>
            </div>

            <label class="auth-check">
                <input type="checkbox" name="terminos" required>
                <span>Acepto los términos y condiciones</span>
            </label>

            <button type="submit">CREAR CUENTA</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>

    <script>
        const passInput = document.getElementById('pass');
        const confirmInput = document.getElementById('confirm_pass');
        const strength = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('strengthText');

        function passwordScore(value) {
            let score = 0;
            if (value.length >= 6) score++;
            if (value.length >= 10) score++;
            if (/[A-Z]/.test(value)) score++;
            if (/[0-9]/.test(value)) score++;
            if (/[^A-Za-z0-9]/.test(value)) score++;
            return Math.min(score, 5);
        }

        function updateStrength() {
            const score = passwordScore(passInput.value);
            strength.dataset.score = score;
            strengthText.textContent = score === 0
                ? 'Ingresa una contraseña'
                : score <= 2
                    ? 'Débil'
                    : score <= 4
                        ? 'Buena'
                        : 'Segura';
            validateConfirm();
        }

        function validateConfirm() {
            const mismatch = confirmInput.value && confirmInput.value !== passInput.value;
            confirmInput.setCustomValidity(mismatch ? 'Las contraseñas no coinciden' : '');
        }

        document.querySelectorAll('.password-toggle').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const showing = input.type === 'text';
                input.type = showing ? 'password' : 'text';
                button.textContent = showing ? '👁' : '●';
                button.setAttribute('aria-label', showing ? 'Mostrar contraseña' : 'Ocultar contraseña');
            });
        });

        passInput.addEventListener('input', updateStrength);
        confirmInput.addEventListener('input', validateConfirm);
    </script>
</body>
</html>

