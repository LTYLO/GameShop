<?php
    session_start();
    if(!isset($_SESSION['usuario'])){
        echo '
        <script>
        alert("Inicia Sesion");
        window.location = "./login.php"
        </script>';
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameShop</title>
    <link rel="shortcut icon" href="img/1000000144.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/paypal.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;500;600&display=swap' rel='stylesheet'>
    <script src="https://www.paypal.com/sdk/js?client-id=BAA9j5ZdZ5T7SRkgj1ozn_I5uS9kwKlu3knDPnYFrgaocZwjLFlXxDUgCqqRgRNpvAG9HG7jzBi_yL2wEc&components=hosted-buttons&disable-funding=venmo&currency=USD"></script>
</head>
<body>
    <div class="iframe-container">
        <iframe src='https://my.spline.design/creditcard-7766fb3259d388ea9bc7afea363fa434/' frameborder='0'></iframe>
    </div>
    <div class="paypal-section">
        <div class="login-container">
            <h2>Iniciar Sesión</h2>
            <div id="paypal-container-FFNBGEUH9A46S"></div>
            <div class="paypal-buttons-container">
                <div id="paypal-button-container"></div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script>
        paypal.HostedButtons({
            hostedButtonId: "FFNBGEUH9A46S",
        }).render("#paypal-container-FFNBGEUH9A46S");

        paypal.Buttons({
            style: { color: 'blue', shape: 'pill', label: 'pay' },
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{ amount: { value: '50000' } }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    alert("Pago completado por " + details.payer.name.given_name);
                });
            },
            onCancel: function (data) {
                alert("Pago cancelado");
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>
