<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];

        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = array('name' => $product_name, 'price' => $product_price, 'quantity' => 1);
        } else {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        }

        echo json_encode(array('status' => 'success'));
    }

    if (isset($_POST['get_cart'])) {
        $cart = array_values($_SESSION['cart']);
        echo json_encode($cart);
    }

    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            echo json_encode(array('status' => 'success'));
        }
    }
}
?>
