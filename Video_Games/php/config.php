<?php
// Configuración de la base de datos
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'registro');

// Conexión a la base de datos
$conexion = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if ($conexion->connect_errno) {
    die("ERROR: No se pudo conectar. " . $conexion->connect_error);
}
?>
