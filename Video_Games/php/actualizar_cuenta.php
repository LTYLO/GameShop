<?php
session_start();
include 'conexion_be.php';

if (!isset($_SESSION['usuario'])) {
    echo '
    <script>
        alert("Inicia Sesión");
        window.location = "./login.php";
    </script>';
    exit;
}

$usuario_id = $_SESSION['id'];
$nombre = $_POST['nombre'];
$alias = $_POST['alias'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Preparar consulta para actualizar los datos del usuario
if (!empty($contrasena)) {
    // Si se proporciona una nueva contraseña, actualizamos todo incluido la contraseña
    $query = "UPDATE usuarios SET Nombre = ?, Alias = ?, Email = ?, Contrasena = ? WHERE id = ?";
    $stmt = $conexion->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ssssi", $nombre, $alias, $correo, $contrasena, $usuario_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo '
        <script>
            alert("Error en la preparación de la consulta.");
            window.location = "./index.php";
        </script>';
        exit;
    }
} else {
    // Si no se proporciona una nueva contraseña, actualizamos los demás datos excepto la contraseña
    $query = "UPDATE usuarios SET Nombre = ?, Alias = ?, Email = ? WHERE id = ?";
    $stmt = $conexion->prepare($query);
    if ($stmt) {
        $stmt->bind_param("sssi", $nombre, $alias, $correo, $usuario_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo '
        <script>
            alert("Error en la preparación de la consulta.");
            window.location = "./index.php";
        </script>';
        exit;
    }
}

echo '
<script>
    alert("Datos actualizados exitosamente.");
    window.location = "./index.php";
</script>';
?>
