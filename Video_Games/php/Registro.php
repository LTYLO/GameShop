<?php
include('config.php');

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$login_button = '';
if (!isset($_SESSION['access_token'])) {
    require_once 'vendor/autoload.php';

    $google_client = new Google_Client();
    $google_client->setClientId('YOUR_CLIENT_ID_HERE');
    $google_client->setClientSecret('YOUR_CLIENT_SECRET_HERE');
    $google_client->setRedirectUri('http://gameshop.com');
    $google_client->addScope('email');
    $google_client->addScope('profile');
    $login_button = '<a href="'.$google_client->createAuthUrl().'"><i class="ri-google-fill"></i> Login With Google</a>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="shortcut icon" href="img\1000000144.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/sign_in.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400..800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="iframe-container">
        <iframe src='https://my.spline.design/softlydescends-72ab28a43f9025c774945d7cd25d3a90/' frameborder='0' width='100%' height='100%'></iframe>
    </div>
    <div class="content">
        <form action="registro_usuario.php" method="POST">
            <h2>Registro</h2>
            <div class="input-box">
                <input type="text" placeholder="Nombre" required name="Nombre">
                <i class="ri-account-circle-line"></i>
            </div>
            <div class="input-box">
                <input type="text" placeholder="Nombre de Usuario" required name="Alias">
                <i class="ri-gamepad-line"></i>
            </div>
            <div class="input-box">
                <input type="email" placeholder="Email" required name="Email">
                <i class="ri-mail-line"></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Contraseña" required name="Contrasena">
                <i class="ri-lock-password-line"></i>
            </div>
            <button type="submit" class="btn">Registrarse</button>
            <div class="button">
                <?php
                echo '<div align="center">'.$login_button . '</div>';
                ?>
            </div>
        </form>
    </div>
</body>
</html>
