<?php
session_start();

// SEGURIDAD: Si no hay sesión iniciada, mandamos al usuario al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$nombreUsuario = $_SESSION['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - Tuortox</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        /* Estilos rápidos para el reloj */
        body { font-family: sans-serif; text-align: center; padding-top: 50px; background-color: #f4f4f4; }
        .reloj-container { background: white; padding: 30px; border-radius: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        #reloj { font-size: 3rem; font-weight: bold; color: #2c3e50; margin: 10px 0; }
        #fecha { color: #7f8c8d; font-size: 1.2rem; }
        .btn-cerrar { margin-top: 20px; display: inline-block; color: black; text-decoration: none; }
    </style>
</head>
<body>

    <div class="reloj-container">
        <h1>¡Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?>!</h1>
        <p>La hora actual es:</p>
        
        <!-- Aquí se mostrará la hora real -->
        <div id="reloj">00:00:00</div>
        <div id="fecha">Cargando fecha...</div>

        <br>
        <a href="logaut.php" class="btn-cerrar">Cerrar Sesión</a>
    </div>

    <script>
        function actualizarReloj() {
            const ahora = new Date();
            
            // Formatear la hora
            const horas = String(ahora.getHours()).padStart(2, '0');
            const minutos = String(ahora.getMinutes()).padStart(2, '0');
            const segundos = String(ahora.getSeconds()).padStart(2, '0');
            
            document.getElementById('reloj').textContent = `${horas}:${minutos}:${segundos}`;

            // Formatear la fecha
            const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('fecha').textContent = ahora.toLocaleDateString('es-ES', opciones);
        }

        // Ejecutar cada segundo
        setInterval(actualizarReloj, 1000);
        
        // Ejecutar inmediatamente al cargar
        actualizarReloj();
    </script>

</body>
</html>