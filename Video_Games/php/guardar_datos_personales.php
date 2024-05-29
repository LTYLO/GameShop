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

$query = "UPDATE usuarios SET Nombre='$nombre', Alias='$alias', Email='$correo' WHERE id='$usuario_id'";

if (mysqli_query($conexion, $query)) {
    echo '
    <script>
    alert("Datos personales actualizados exitosamente.");
    window.location = "./Usuario.php";
    </script>';
} else {
    echo '
    <script>
    alert("Error al actualizar los datos.");
    window.location = "./Usuario.php";
    </script>';
}

mysqli_close($conexion);
?>
