<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="shortcut icon" href="img\1000000144.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="css/login.css">

    <!-- Remixicons link -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>

    <!-- Google Fonts link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400..800&display=swap" rel="stylesheet">
</head>
<body>

    <div class="iframe-container">
    <iframe src='https://my.spline.design/threadsfeatureheaderanimation-5168e6f1b704f6bc33bc08d8202ac7e1/' frameborder='0' width='100%' height='100%'></iframe>
    </div>

    <div class="content">
        <form action="login_usuario_be.php" method = 'POST'>
            <h2>Iniciar Sesión</h2>

            <div class="input-box">
                <input type="email" placeholder="Correo Electrónico" required name = "Email">
                <i class="ri-mail-line"></i>
            </div>

            <div class="input-box">
                <input type="password" placeholder="Contraseña" required name = "Contrasena">
                <i class="ri-lock-fill"></i>
            </div>

            <div class="remember">
                <label><input type="checkbox">Recordarme</label>
                <a href="#">Olvidé mi contraseña</a>
            </div>

            <button type="submit" class="btn">Iniciar Sesión</button>
            <button type="button" class="btn" onclick="window.location.href='Registro.php'">Registrarse</button>
            <div class="button">
                <a href="#">
                   <i class="ri-google-fill"></i>
                   Google
                </a>
            </div>
        </form>
    </div>

    
</body>
</html>
