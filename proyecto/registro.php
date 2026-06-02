<?php 
include("conexion.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CAPTURA Y LIMPIEZA
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $email  = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = mysqli_real_escape_string($conexion, $_POST['pass']);

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
    <title>Registro - Tuortox</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="auth-container">
    <div class="auth-card">
        <h1>REGISTRO</h1>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="pass" placeholder="Contraseña" required>
            <button type="submit">CREAR CUENTA</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a></p>
    </div>
</body>
</html>
