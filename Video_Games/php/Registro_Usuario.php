<?php
session_start();
include 'config.php';

$Nombre = $_POST['Nombre'];
$Alias = $_POST['Alias'];
$Email = $_POST['Email'];
$contrasena = $_POST['Contrasena'];

$check_alias_query = "SELECT * FROM usuarios WHERE Alias='$Alias'";
$result_alias = mysqli_query($conexion, $check_alias_query);

if (mysqli_num_rows($result_alias) > 0) {
    echo '
    <script>
        alert("Este Nombre de Usuario ya está registrado.");
        window.location = "./registro.php";
    </script>';
    exit();
}

$check_email_query = "SELECT * FROM usuarios WHERE Email='$Email'";
$result_email = mysqli_query($conexion, $check_email_query);

if (mysqli_num_rows($result_email) > 0) {
    echo '
    <script>
        alert("Este correo electrónico ya está registrado.");
        window.location = "./registro.php";
    </script>';
    exit();
}

$query = "INSERT INTO usuarios (Nombre, Alias, Email, contrasena) 
          VALUES ('$Nombre', '$Alias', '$Email', '$contrasena')";

if (mysqli_query($conexion, $query)) {
    echo '
    <script>
        alert("Usuario almacenado exitosamente");
        window.location = "./login.php";
    </script>';
} else {
    echo '
    <script>
        alert("Error al almacenar el usuario");
        window.location = "./registro.php";
    </script>';
}

mysqli_close($conexion);
?>

