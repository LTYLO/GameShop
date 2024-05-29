<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

// Crear objeto de cliente de Google API para llamar a la API de Google
$google_client = new Google_Client();

// Configurar el ID del cliente OAuth 2.0
$google_client->setClientId('YOUR_CLIENT_ID_HERE');

// Configurar la clave secreta del cliente OAuth 2.0
$google_client->setClientSecret('YOUR_CLIENT_SECRET_HERE');

// Configurar la URI de redirección OAuth 2.0
$google_client->setRedirectUri('http://localhost/Registro.php'); // Asegúrate de que coincida con Google Cloud Console

// Obtener el email y perfil
$google_client->addScope('email');
$google_client->addScope('profile');

// Verificar si hay un código de autorización en la URL
if (isset($_GET['code'])) {
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (!isset($token['error'])) {
        $google_client->setAccessToken($token['access_token']);
        $_SESSION['access_token'] = $token['access_token'];
        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();

        // Almacenar datos obtenidos en variables de sesión
        if (!empty($data['given_name'])) {
            $_SESSION['user_first_name'] = $data['given_name'];
        }

        if (!empty($data['family_name'])) {
            $_SESSION['user_last_name'] = $data['family_name'];
        }

        if (!empty($data['email'])) {
            $_SESSION['user_email_address'] = $data['email'];
        }

        if (!empty($data['picture'])) {
            $_SESSION['user_image'] = $data['picture'];
        }

        // Guardar los datos en la base de datos   
        include('config.php');
        
        $Nombre = $_SESSION['user_first_name'];
        $Apellido = $_SESSION['user_last_name'];
        $Alias = $Nombre . "_" . $Apellido;
        $Email = $_SESSION['user_email_address'];
        $contrasena = ''; // No tenemos contraseña del OAuth, dejarla vacía

        $check_email_query = "SELECT * FROM usuarios WHERE Email='$Email'";
        $result_email = $conexion->query($check_email_query);

        if ($result_email->num_rows == 0) {
            // Insertar solo si el usuario no existe
            $query = "INSERT INTO usuarios (Nombre, Alias, Email, contrasena) 
                      VALUES ('$Nombre', '$Alias', '$Email', '$contrasena')";
            if ($conexion->query($query) === TRUE) {
                // Usuario almacenado exitosamente
                echo '
                <script>
                    alert("Usuario almacenado exitosamente");
                    window.location = "./perfil.php";
                </script>';
            } else {
                // Error al almacenar el usuario
                echo '
                <script>
                    alert("Error al almacenar el usuario");
                    window.location = "./registro.php";
                </script>';
            }
        } else {
            // Si el usuario ya existe, redirigir a perfil
            echo '
            <script>
                window.location = "./perfil.php";
            </script>';
        }
    }
}

// Verificar si el usuario ya está autenticado
if (!isset($_SESSION['access_token'])) {
    header('Location: ' . $google_client->createAuthUrl());
    exit();
}
?>
