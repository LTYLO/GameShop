<?php
session_start();
include 'conexion_be.php'; 

$correo = $_POST['Email'];
$Password = $_POST['Contrasena'];

// Verificación específica para redirigir al administrador
if ($correo == 'Salinaskevin031@gmail.com' && $Password == 'Kevin') {
    $_SESSION['usuario'] = $correo;
    $_SESSION['id'] = 'admin'; 
    header("Location: ./Admin.php");
    exit;
}

$validar_login = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Email = '$correo' AND Contrasena = '$Password'");

if (mysqli_num_rows($validar_login) > 0) {
    $usuario = mysqli_fetch_assoc($validar_login);
    $_SESSION['usuario'] = $correo;
    $_SESSION['id'] = $usuario['id'];  // Guarda el ID del usuario en la sesión
    header("Location: ./index.php");
    exit;
} else {
    echo '
    <script>
    alert("Usuario no existe, por favor verifique los datos");
    window.location = "./login.php";
    </script>';
    exit;
}
?>
