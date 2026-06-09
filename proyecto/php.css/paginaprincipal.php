<?php
session_start();
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
    <style>
        body { margin: 0; overflow: hidden; background-color: #050508; font-family: 'Segoe UI', sans-serif; color: #fff; }
        
        /* El fondo animado */
        #bg-canvas { position: fixed; top: 0; left: 0; z-index: 1; }
        
        /* Interfaz */
        #overlay {
            position: relative;
            z-index: 10;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            pointer-events: none;
            background: radial-gradient(circle, transparent 20%, rgba(0,0,0,0.7) 100%);
        }

        .content { text-align: center; pointer-events: auto; }
        .welcome { font-size: 1rem; letter-spacing: 6px; text-transform: uppercase; opacity: 0.8; margin-bottom: 5px; }
        #reloj { font-size: 6rem; font-weight: bold; color: #00f3ff; text-shadow: 0 0 30px rgba(0,243,255,0.5); margin: 0; font-family: monospace; }
        #fecha { letter-spacing: 4px; text-transform: uppercase; font-size: 0.85rem; opacity: 0.6; margin-top: 5px; }
        
        .btn-cerrar {
            margin-top: 40px;
            display: inline-block;
            padding: 12px 35px;
            border: 1px solid #ff2d55;
            color: #ff2d55;
            text-decoration: none;
            border-radius: 4px;
            transition: 0.3s;
            background: rgba(255, 45, 85, 0.1);
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .btn-cerrar:hover { background: #ff2d55; color: #fff; box-shadow: 0 0 25px rgba(255, 45, 85, 0.5); }
    </style>
</head>
<body>

<canvas id="bg-canvas"></canvas>

<div id="overlay">
    <div class="content">
        <div class="welcome">BIENVENIDO, <?php echo htmlspecialchars($nombreUsuario); ?></div>
        <div id="reloj">00:00:00</div>
        <div id="fecha">INICIALIZANDO...</div>
        <a href="logaut.php" class="btn-cerrar">Cerrar Sesión</a>
    </div>
</div>

<script>
    // --- LÓGICA DEL RELOJ ---
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('reloj').innerText = h + ":" + m + ":" + s;
        
        const options = { weekday: 'long', day: 'numeric', month: 'long' };
        document.getElementById('fecha').innerText = now.toLocaleDateString('es-ES', options);
    }
    setInterval(updateClock, 1000);
    updateClock();

    // --- FONDO DE PARTÍCULAS (SIN LIBRERÍAS) ---
    const canvas = document.getElementById('bg-canvas');
    const ctx = canvas.getContext('2d');
    let particles = [];

    function initCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        particles = [];
        for (let i = 0; i < 150; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                size: Math.random() * 2 + 0.5,
                speedX: (Math.random() - 0.5) * 0.5,
                speedY: (Math.random() - 0.5) * 0.5,
                opacity: Math.random()
            });
        }
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#fff';

        particles.forEach(p => {
            p.x += p.speedX;
            p.y += p.speedY;

            // Teletransporte al otro lado si sale de pantalla
            if (p.x > canvas.width) p.x = 0;
            if (p.x < 0) p.x = canvas.width;
            if (p.y > canvas.height) p.y = 0;
            if (p.y < 0) p.y = canvas.height;

            ctx.globalAlpha = p.opacity;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fill();
        });

        requestAnimationFrame(animate);
    }

    window.addEventListener('resize', initCanvas);
    initCanvas();
    animate();
</script>

</body>
</html>
