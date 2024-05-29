<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo '
    <script>
    alert("Inicia Sesion");
    window.location = "./login.php";
    </script>';
    exit;
}
$server = "localhost";
$user = "root";
$pass = "";
$db = "registro";

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_errno) {
    die("Conexión Fallida: " . $conexion->connect_error);
}

// Manejar la búsqueda
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $conexion->real_escape_string($_GET['search']);
}

$check_alias_query = "SELECT id, Nombre, Precio, Descripcion FROM productos WHERE activo = 1";

if ($search_query) {
    $check_alias_query .= " AND Nombre LIKE '%$search_query%'";
}

$result_alias = mysqli_query($conexion, $check_alias_query);

// Añadir un producto al carrito
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    $cart_item = array(
        'id' => $product_id,
        'name' => $product_name,
        'price' => $product_price,
        'quantity' => 1
    );

    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        // Verificar si el producto ya está en el carrito
        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $cart[] = $cart_item;
        }
    } else {
        $cart = array($cart_item);
    }

    $_SESSION['cart'] = $cart;
    echo json_encode(array('status' => 'success'));
    exit;
}

// Eliminar un producto del carrito
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        foreach ($cart as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($cart[$key]);
                break;
            }
        }
        $_SESSION['cart'] = array_values($cart);
    }
    echo json_encode(array('status' => 'success'));
    exit;
}

// Obtener el contenido del carrito
if (isset($_POST['get_cart'])) {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    echo json_encode($cart);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameShop</title>
    <link rel="shortcut icon" href="img/1000000144.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/Catalogo.css">
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
            <li><a href="Usuario.php">Usuario</a></li>
        </ul>
        <div class="nav-icons">
            <div class="container">
                <form action="catalogo.php" method="GET">
                    <input type="text" name="search" placeholder="Buscar">
                    <button type="submit" class="btn">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
            <a href="#" onclick="mostrarCarrito()">
                <i class="bx bx-cart"></i>
            </a>
            <div class="bx bx-menu" id="menu-icon"></div>
        </div>
    </header>
    <section class="contenido">
        <div class="mostrador" id="mostrador">
            <?php 
            if (mysqli_num_rows($result_alias) > 0) {
                $counter = 0;
                while($row = mysqli_fetch_assoc($result_alias)){ 
                    if ($counter % 4 == 0) {
                        echo '<div class="fila">';
                    }
            ?>
                    <div class="item" onclick="cargar(this)" data-id="<?php echo $row['id']; ?>" data-descripcion="<?php echo htmlspecialchars($row['Descripcion'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="contenedor-foto">
                            <img src="img/<?php echo $row['id']; ?>/Principal.png" alt="">
                        </div>
                        <p class="descripcion"><?php echo $row['Nombre']; ?></p>
                        <span class="precio">$ <?php echo $row['Precio']; ?></span>
                    </div>
            <?php 
                    $counter++;
                    if ($counter % 4 == 0) {
                        echo '</div>';
                    }
                }
                if ($counter % 4 != 0) {
                    echo '</div>';
                }
            } else {
                echo '<div class="alert">No se encontraron productos.</div>';
            }
            ?>
        </div>
        <!-- CONTENEDOR DEL ITEM SELECCIONADO -->
        <!-- CONTENEDOR DEL ITEM SELECCIONADO -->
<div class="seleccion" id="seleccion">
    <div class="cerrar" onclick="cerrar()">
        &#x2715;
    </div>
    <div class="info" id="product_info">
        <img src="" alt="" id="img">
        <h2 id="modelo"></h2>
        <p id="descripcion"></p>
        <span class="precio" id="precio"></span>
        <div class="fila">
            <button id="add_to_cart" data-id="">AGREGAR AL CARRITO</button>
        </div>
    </div>
</div>
>
        <!-- MODAL DEL CARRITO -->
        <div id="cart_modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="cerrarCarrito()">&times;</span>
                <h2>Carrito de Compras</h2>
                <div id="cart_content"></div>
                <button id="comprar_ahora" class="comprar-btn">Comprar ahora</button>
            </div>
        </div>
    </section>

    <script src="js/script_1.js"></script>
</body>
</html>