<?php 
include("conexion.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CAPTURA Y LIMPIEZA
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $email  = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = mysqli_real_escape_string($conexion, $_POST['pass']);

    // SQL EXACTO: tabla 'usuario', columnas 'nombre', 'correo', 'pass'
    $sql = "INSERT INTO usuario (nombre, email, pass) VALUES ('$nombre', '$email', '$password')";

    
    if (mysqli_query($conexion, $sql)) {
        echo "<script>
                alert('¡Usuario registrado correctamente!');
                window.location='login.php';
              </script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
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
