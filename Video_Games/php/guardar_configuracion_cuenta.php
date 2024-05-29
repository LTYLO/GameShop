<?php
session_start();
include 'conexion_be.php'; 

if (!isset($_SESSION['usuario'])) {
    echo '
    <script>
    alert("Inicia Sesion");
    window.location = "./login.php";
    </script>';
    exit;
}

$usuario_id = $_SESSION['id'];
$nombre = $_POST['nombre'];
$alias = $_POST['alias'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

$query = "UPDATE usuarios SET Nombre='$nombre', Alias='$alias', Email='$correo'";

if (!empty($contrasena)) {
    $query .= ", Contrasena='".password_hash($contrasena, PASSWORD_DEFAULT)."'";
}

$query .= " WHERE id='$usuario_id'";

if (mysqli_query($conexion, $query)) {
    echo '
    <script>
    alert("Configuración de cuenta actualizada exitosamente.");
    window.location = "./Usuario.php";
    </script>';
} else {
    echo '
    <script>
    alert("Error al actualizar la configuración.");
    window.location = "./Usuario.php";
    </script>';
}

mysqli_close($conexion);
?>
