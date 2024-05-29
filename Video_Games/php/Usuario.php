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

// Consulta los datos del usuario utilizando prepared statements
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        echo '
        <script>
            alert("Error al obtener datos del usuario.");
            window.location = "./index.php";
        </script>';
        exit;
    }

    $stmt->close();
} else {
    echo '
    <script>
        alert("Error en la preparación de la consulta.");
        window.location = "./index.php";
    </script>';
    exit;
}

// Obtener el contenido del carrito desde la sesión
$cart_contenido = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameShop</title>
    <link rel="icon" href="img/1000000144.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/usuario.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;500;600&display=swap' rel='stylesheet'>
</head>
<body>
    <header>
        <a href="index.php" class="logo"><i class='bx bx-home'></i>GameShop</a>
        <ul class="navlist">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="catalogo.php">Tienda</a></li>
        </ul>
        <div class="nav-icons">
            <a><img src="img/user-3-fill.png" alt="usuario icon"></a>
        </div>
    </header>

    <div class="container-sidebar">
        <nav class="sidebar">
            <ul>
                <li><a href="#" id="personal-data-link">Datos personales</a></li>
                <li><a href="#" id="account-settings-link">Configurar mi cuenta</a></li>
                <li><a href="#" id="mi-compras-enlace">Mis Compras</a></li>
                <li><a href="cerrar_sesion.php">Cerrar sesión</a></li>
            </ul>
        </nav>

        <main>
            <!-- Contenido principal -->
        </main>
    </div>

    <!-- Modal de datos personales -->
    <div class="modal" id="datos-personales-modal">
        <div class="modal-content">
            <h2>Datos Personales</h2>
            <form id="datos-personales-form" method="POST" action="guardar_datos_personales.php">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <span><?php echo htmlspecialchars($usuario['Nombre']); ?></span>
                </div>
                <div class="form-group">
                    <label for="alias">Alias:</label>
                    <span><?php echo htmlspecialchars($usuario['Alias']); ?></span>
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico:</label>
                    <span><?php echo htmlspecialchars($usuario['Email']); ?></span>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de configurar mi cuenta -->
   <!-- Modal de configurar mi cuenta -->
<div class="modal" id="configurar-cuenta-modal">
    <div class="modal-content">
        <h2>Configurar mi Cuenta</h2>
        <form id="configurar-cuenta-form" method="POST" action="actualizar_cuenta.php">
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['Nombre']); ?>" required>
    </div>
    <div class="form-group">
        <label for="alias">Alias:</label>
        <input type="text" id="alias" name="alias" value="<?php echo htmlspecialchars($usuario['Alias']); ?>" required>
    </div>
    <div class="form-group">
        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['Email']); ?>" required>
    </div>
    <div class="form-group">
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena">
        <small>Deje en blanco para mantener la contraseña actual.</small>
    </div>
    <button type="submit">Guardar cambios</button>
</form>

        </form>
    </div>
</div>


    <!-- Modal de mis compras -->
    <div class="modal" id="mis-compras-modal">
        <div class="modal-content">
            <h2>Mis Compras</h2>
            <table id="purchases-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cart_contenido)) : ?>
                        <?php foreach ($cart_contenido as $item) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3">No hay compras realizadas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modals = document.querySelectorAll('.modal');
        const modalTriggers = {
            'personal-data-link': 'datos-personales-modal',
            'account-settings-link': 'configurar-cuenta-modal',
            'mi-compras-enlace': 'mis-compras-modal'
        };

        // Add listeners to links to open modals
        Object.keys(modalTriggers).forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('click', () => {
                    const modal = document.getElementById(modalTriggers[id]);
                    if (modal) {
                        modal.style.display = 'block';
                    }
                });
            }
        });

        // Add listeners to close buttons to close modals
        const closeButtons = document.querySelectorAll('.close-btn');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                closeModal(btn.closest('.modal'));
            });
        });

        // Function to close the modal
        function closeModal(modal) {
            if (modal) {
                modal.style.display = 'none';
                const activeLink = document.querySelector('.navlist .active');
                if (activeLink) {
                    activeLink.classList.remove('active');
                }
            }
        }

        // Close the modal when clicking outside the modal content
        window.addEventListener('click', (event) => {
            modals.forEach(modal => {
                if (event.target === modal) {
                    closeModal(modal);
                }
            });
        });
    });
    </script>
</body>
</html>
