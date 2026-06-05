<?php
session_start(); // Iniciamos sesión para recordar al usuario
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = mysqli_real_escape_string($conexion, $_POST['pass']);

    // 1. Buscamos al usuario solo por su email
    $sql = "SELECT * FROM usuario WHERE email = '$email'";
    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);

        // 2. COMPARACIÓN: ¿La contraseña coincide con la de la base de datos?
        if (password_verify($password, $usuario['pass'])) {
            // LOGIN EXITOSO
            $_SESSION['id_usuario'] = $usuario['idUsuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            
            header("Location: panel.php"); // Redirige a tu página principal
            exit();
        } else {
            // CONTRASEÑA INCORRECTA
            echo "<script>
                    alert('La contraseña es incorrecta. Intenta de nuevo.');
                    window.history.back();
                  </script>";
        }
    } else {
        // EL CORREO NO EXISTE
        echo "<script>
                alert('Este correo no está registrado.');
                window.history.back();
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tuortox</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="auth-container login-page">
    <div class="auth-card">
        <div class="auth-logo" aria-label="Logo de Tuortox">T</div>
        <h1>Bienvenido de nuevo</h1>
        <p class="auth-subtitle">Inicia sesión para continuar</p>

        <form method="POST">
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

            <div class="auth-options">
                <label class="auth-check">
                    <input type="checkbox" name="recordarme">
                    <span>Recordarme</span>
                </label>
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit">ENTRAR</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </div>

    <script>
        document.querySelectorAll('.password-toggle').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const showing = input.type === 'text';
                input.type = showing ? 'password' : 'text';
                button.textContent = showing ? '👁' : '●';
                button.setAttribute('aria-label', showing ? 'Mostrar contraseña' : 'Ocultar contraseña');
            });
        });
    </script>
</body>
</html>
