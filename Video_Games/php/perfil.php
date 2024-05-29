<?php
include('config.php');
include('autentificacion.php'); // Incluye la autenticación de Google OAuth

// Verificar si el usuario está autenticado
if (!isset($_SESSION['access_token'])) {
    header('Location: registro.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="content">
        <h2>Perfil de Usuario</h2>
        <img src="<?php echo $_SESSION['user_image']; ?>" alt="User Image" class="img-responsive img-circle img-thumbnail" />
        <h3>Nombre: <?php echo $_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']; ?></h3>
        <h3>Email: <?php echo $_SESSION['user_email_address']; ?></h3>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
