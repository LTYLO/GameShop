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
    <link rel="stylesheet" href="css/index.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;500;600;800&display=swap' rel='stylesheet'>
</head>
<body>
<header>
        <a href="#" class="logo"><i class='bx bx-home'></i>GameShop</a>
        <ul class="navlist">
            <li><a href="#" class="active">Home</a></li>
            <li><a href="Catalogo.php">Tienda</a></li>
            <li><a href="Usuario.php">Usuario</a></li>
        </ul>
        <div class="nav-icons">
            <a href=""Usuario.php><box-icon name='user-circle'></box-icon></a>
            <a href="#" onclick="mostrarCarrito()"><i class="bx bx-cart"></i></a>
            <div class="bx bx-menu" id="menu-icon"></div>
        </div>
    </header>
    <!-- Home -->
    <section class="home" id="home">
        <div class="text-container">
            <h1>Bienvenido a GameShop</h1>
            <a href="Catalogo.php" class="btn green-btn">Comprar ahora</a>
        </div>
    </section>
    <div class="iframe-container">
    <iframe src='https://my.spline.design/miniastronaut-f99b8589973ee51e86bd5127be5c156a/' frameborder='0'></iframe>
</div>

    <!-- MODAL DEL CARRITO -->
    <div id="cart_modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarCarrito()">&times;</span>
            <h2>Carrito de Compras</h2>
            <div id="cart_content"></div>
            <button id="comprar_ahora" class="comprar-btn">Comprar ahora</button>
        </div>
    </div>

    <script src="js/script_1.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script>
       function mostrarCarrito() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            var cart = JSON.parse(xhr.responseText);
            var cartHTML = "<ul>";
            cart.forEach(function(item) {
                cartHTML += "<li>" + item.name + " - $" + item.price + " x " + item.quantity +
                            " <button onclick='removeFromCart(" + item.id + ")'>Eliminar</button></li>";
            });
            cartHTML += "</ul>";
            document.getElementById("cart_content").innerHTML = cartHTML;
            document.getElementById("cart_modal").style.display = "block";
        }
    };
    xhr.send("get_cart=1");
}

function removeFromCart(product_id) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
                mostrarCarrito();
            }
        }
    };
    xhr.send("remove_from_cart=1&product_id=" + product_id);
}

function cerrarCarrito() {
    document.getElementById('cart_modal').style.display = 'none';
}

document.getElementById('comprar_ahora').addEventListener('click', function() {
    window.location.href = 'Pay.php';
});
    </script>
</body>
</html>
