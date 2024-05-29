<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo '
    <script>
        alert("Inicia Sesión");
        window.location = "./login.php";
    </script>';
    exit;
}

$servername = "localhost"; // Cambiar si es necesario
$username = "root"; // Cambiar si es necesario
$password = ""; // Cambiar si es necesario
$dbname = "registro"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para obtener usuarios
function obtenerUsuarios($conn) {
    $sql = "SELECT id, nombre, alias, email, contrasena FROM usuarios";
    $result = $conn->query($sql);
    return $result;
}

// Función para obtener productos
function obtenerProductos($conn) {
    $sql = "SELECT id, nombre, descripcion, precio, id_categoria, activo FROM productos";
    $result = $conn->query($sql);
    return $result;
}

// Agregar Usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form-agregar-usuario'])) {
    $nombre = $_POST['nombre'];
    $alias = $_POST['alias'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    $sql = "INSERT INTO usuarios (nombre, alias, email, contrasena) VALUES ('$nombre', '$alias', '$email', '$contrasena')";
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo usuario agregado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Agregar Producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form-agregar-producto'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $id_categoria = $_POST['id_categoria'];
    $activo = isset($_POST['activo']) ? 1 : 0;

    $sql = "INSERT INTO productos (nombre, descripcion, precio, id_categoria, activo) VALUES ('$nombre', '$descripcion', '$precio', '$id_categoria', '$activo')";
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo producto agregado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Eliminar Usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form-eliminar-usuario'])) {
    $id_usuario = $_POST['id_usuario'];

    $sql = "DELETE FROM usuarios WHERE id='$id_usuario'";
    if ($conn->query($sql) === TRUE) {
        echo "Usuario eliminado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Eliminar Producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form-eliminar-producto'])) {
    $id_producto = $_POST['id_producto'];

    $sql = "DELETE FROM productos WHERE id='$id_producto'";
    if ($conn->query($sql) === TRUE) {
        echo "Producto eliminado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo">
            <i class="ri-home-smile-line"></i>
            <a href="#">GameShop</a>
        </div>
        <nav>
            <ul>
                <li><a href="login.php" class="home-link">Panel Administrativo</a></li>
            </ul>
        </nav>
        <div class="cart-icon">
            <img id="iconimg" src="img/user-3-fill (1).png" alt="admin">
        </div>
    </header>

    <div class="sidebar">
        <a href="#" class="option" data-panel="usuarios">Usuarios</a>
        <a href="#" class="option" data-panel="productos">Productos</a>
        <a href="#" class="option" data-panel="agregar-usuario">Agregar Usuario</a>
        <a href="#" class="option" data-panel="agregar-producto">Agregar Producto</a>
        <a href="#" class="option" data-panel="eliminar-usuario">Eliminar Usuario</a>
        <a href="#" class="option" data-panel="eliminar-producto">Eliminar Producto</a>
        <div class="logout-btn">
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </div>
    </div>

    <div class="main">
        <div id="panel-usuarios" class="panel">
            <h2>Usuarios</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Alias</th>
                        <th>Email</th>
                        <th>Contraseña</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usuarios = obtenerUsuarios($conn);
                    if ($usuarios->num_rows > 0) {
                        while($row = $usuarios->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["nombre"] . "</td>";
                            echo "<td>" . $row["alias"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["contrasena"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No hay usuarios disponibles</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div id="panel-productos" class="panel">
            <h2>Productos</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>ID Categoría</th>
                        <th>Activo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $productos = obtenerProductos($conn);
                    if ($productos->num_rows > 0) {
                        while($row = $productos->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["nombre"] . "</td>";
                            echo "<td>" . $row["descripcion"] . "</td>";
                            echo "<td>" . $row["precio"] . "</td>";
                            echo "<td>" . $row["id_categoria"] . "</td>";
                            echo "<td>" . ($row["activo"] ? 'Sí' : 'No') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay productos disponibles</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div id="panel-agregar-usuario" class="panel">
            <h2>Agregar Usuario</h2>
            <form id="form-agregar-usuario" method="post">
                <input type="hidden" name="form-agregar-usuario" value="1">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br>
                <label for="alias">Alias:</label>
                <input type="text" id="alias" name="alias" required><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required><br>
                <button type="submit">Agregar Usuario</button>
            </form>
        </div>

        <div id="panel-agregar-producto" class="panel">
            <h2>Agregar Producto</h2>
            <form id="form-agregar-producto" method="post">
                <input type="hidden" name="form-agregar-producto" value="1">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br>
                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" required><br>
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" required><br>
                <label for="id_categoria">ID Categoría:</label>
                <input type="number" id="id_categoria" name="id_categoria" required><br>
                <label for="activo">Activo:</label>
                <input type="checkbox" id="activo" name="activo"><br>
                <button type="submit">Agregar Producto</button>
            </form>
        </div>

        <div id="panel-eliminar-usuario" class="panel">
            <h2>Eliminar Usuario</h2>
            <form id="form-eliminar-usuario" method="post">
                <input type="hidden" name="form-eliminar-usuario" value="1">
                <label for="id_usuario">ID del Usuario:</label>
                <input type="number" id="id_usuario" name="id_usuario" required><br>
                <button type="submit">Eliminar Usuario</button>
            </form>
        </div>

        <div id="panel-eliminar-producto" class="panel">
            <h2>Eliminar Producto</h2>
            <form id="form-eliminar-producto" method="post">
                <input type="hidden" name="form-eliminar-producto" value="1">
                <label for="id_producto">ID del Producto:</label>
                <input type="number" id="id_producto" name="id_producto" required><br>
                <button type="submit">Eliminar Producto</button>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
     document.addEventListener("DOMContentLoaded", function() {
    const options = document.querySelectorAll('.option');
    const panels = document.querySelectorAll('.panel');

    options.forEach(option => {
        option.addEventListener('click', function() {
            const panelId = this.getAttribute('data-panel');
            panels.forEach(panel => {
                if (panel.id === `panel-${panelId}`) {
                    panel.style.display = 'block';
                } else {
                    panel.style.display = 'none';
                }
            });
        });
    });
});

    </script>
</body>
</html>
