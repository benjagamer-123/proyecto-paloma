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
        if ($usuario['pass'] === $password) {
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
    <title>Login - Tuortox</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="auth-container">
    <div class="auth-card">
        <h1>INICIAR SESIÓN</h1>
        <form method="POST">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="pass" placeholder="Contraseña" required>
            <button type="submit">ENTRAR</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </div>
</body>
</html>
