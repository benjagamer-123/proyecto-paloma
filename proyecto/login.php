<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = mysqli_real_escape_string($conexion, $_POST['pass']);

    $sql = "SELECT * FROM usuario WHERE correo = '$email' AND pass = '$password'";
    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        $_SESSION['id_usuario'] = $usuario['idUsuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        
        header("Location: panel.php"); // O la página principal de tu app
    } else {
        echo "<script>alert('Datos incorrectos');</script>";
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
        <h1>LOGIN</h1>
        <form method="POST">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="pass" placeholder="Contraseña" required>
            <button type="submit">ENTRAR</button>
        </form>
    </div>
</body>
</html>
