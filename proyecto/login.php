<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = mysqli_real_escape_string($conexion, $_POST['pass']);

    $sql = "SELECT * FROM usuario WHERE email = '$email'";
    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);
        if (password_verify($password, $usuario['pass'])) {
            $_SESSION['id_usuario'] = $usuario['idUsuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            header("Location: paginaprincipal.php");
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Correo no registrado.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tuortox</title>
    <style>
        /* CSS INTEGRADO PARA EVITAR FALLOS */
        body { 
            margin: 0; 
            overflow: hidden; 
            background-color: #050508; 
            font-family: 'Segoe UI', sans-serif; 
            color: #fff; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh;
        }

        #bg-canvas { position: fixed; top: 0; left: 0; z-index: 1; }

        .auth-card {
            position: relative;
            z-index: 10;
            background: rgba(10, 10, 20, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 4px;
            border: 1px solid rgba(0, 243, 255, 0.3);
            width: 100%;
            max-width: 380px;
            text-align: center;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.8);
        }

        .auth-logo {
            width: 50px; height: 50px;
            border: 2px solid #00f3ff;
            color: #00f3ff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 1.5rem;
            margin: 0 auto 20px;
            text-shadow: 0 0 10px #00f3ff;
        }

        h1 { font-size: 1.2rem; letter-spacing: 4px; text-transform: uppercase; margin-bottom: 10px; }
        .auth-subtitle { font-size: 0.8rem; color: rgba(255,255,255,0.5); margin-bottom: 30px; }

        form { text-align: left; }
        label { display: block; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px; color: #00f3ff; }

        .input-wrapper { position: relative; margin-bottom: 20px; }
        .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); opacity: 0.7; }
        
        input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 2px;
            outline: none;
            box-sizing: border-box;
        }

        input:focus { border-color: #00f3ff; background: rgba(0,243,255,0.05); }

        .password-toggle {
            position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #fff; cursor: pointer; opacity: 0.6;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: transparent;
            border: 1px solid #00f3ff;
            color: #00f3ff;
            font-weight: bold;
            letter-spacing: 3px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        button[type="submit"]:hover { background: #00f3ff; color: #000; box-shadow: 0 0 20px rgba(0,243,255,0.4); }

        .auth-options { display: flex; justify-content: space-between; font-size: 0.7rem; margin-bottom: 20px; }
        a { color: #00f3ff; text-decoration: none; }
        a:hover { color: #ff2d55; }
        p { font-size: 0.8rem; color: rgba(255,255,255,0.4); margin-top: 20px; }
    </style>
</head>
<body>

    <canvas id="bg-canvas"></canvas>

    <div class="auth-card">
        <div class="auth-logo">T</div>
        <h1>BIENVENIDO</h1>
        <p class="auth-subtitle">Acceso al Sistema Tuortox</p>

        <form method="POST">
            <label>Correo Electrónico</label>
            <div class="input-wrapper">
                <span class="input-icon">📧</span>
                <input type="email" name="email" placeholder="email@ejemplo.com" required>
            </div>

            <label>Contraseña</label>
            <div class="input-wrapper">
                <span class="input-icon">🔒</span>
                <input id="pass" type="password" name="pass" placeholder="••••••••" required>
                <button class="password-toggle" type="button" onclick="togglePass()">👁</button>
            </div>

            <div class="auth-options">
                <label style="display:flex; align-items:center; gap:5px;">
                    <input type="checkbox"> Recordarme
                </label>
                <a href="#">¿Olvidaste tu clave?</a>
            </div>

            <button type="submit">ENTRAR</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </div>

    <script>
        // FONDO DE ESTRELLAS
        const canvas = document.getElementById('bg-canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function init() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            particles = [];
            for(let i=0; i<150; i++) {
                particles.push({
                    x: Math.random()*canvas.width,
                    y: Math.random()*canvas.height,
                    size: Math.random()*2,
                    sx: (Math.random()-0.5)*0.5,
                    sy: (Math.random()-0.5)*0.5,
                    o: Math.random()
                });
            }
        }

        function animate() {
            ctx.clearRect(0,0,canvas.width, canvas.height);
            ctx.fillStyle = "#fff";
            particles.forEach(p => {
                p.x += p.sx; p.y += p.sy;
                if(p.x > canvas.width) p.x = 0; if(p.x < 0) p.x = canvas.width;
                if(p.y > canvas.height) p.y = 0; if(p.y < 0) p.y = canvas.height;
                ctx.globalAlpha = p.o;
                ctx.beginPath(); ctx.arc(p.x, p.y, p.size, 0, Math.PI*2); ctx.fill();
            });
            requestAnimationFrame(animate);
        }

        function togglePass() {
            const p = document.getElementById('pass');
            p.type = p.type === 'password' ? 'text' : 'password';
        }

        window.addEventListener('resize', init);
        init(); animate();
    </script>
</body>
</html>
