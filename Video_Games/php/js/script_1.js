let mostrador = document.getElementById("mostrador");
let seleccion = document.getElementById("seleccion");
let imgSeleccionada = document.getElementById("img");
let modeloSeleccionado = document.getElementById("modelo");
let descripSeleccionada = document.getElementById("descripcion");
let precioSeleccionado = document.getElementById("precio");

function cargar(item) {
    quitarBordes();
    mostrador.style.width = "60%";
    seleccion.style.width = "40%";
    seleccion.style.opacity = "1";
    item.style.border = "2px solid red";

    imgSeleccionada.src = item.getElementsByTagName("img")[0].src;
    modeloSeleccionado.innerHTML = item.getElementsByTagName("p")[0].innerHTML;
    descripSeleccionada.innerHTML = item.getAttribute("data-descripcion");
    precioSeleccionado.innerHTML = item.getElementsByTagName("span")[0].innerHTML;

    const addToCartButton = document.getElementById("add_to_cart");

    // Eliminar cualquier evento previo para evitar duplicaciones
    addToCartButton.replaceWith(addToCartButton.cloneNode(true));

    // Reasignar el nuevo evento
    document.getElementById("add_to_cart").addEventListener('click', function() {
        addToCart(item);
    });
}

function cerrar() {
    mostrador.style.width = "100%";
    seleccion.style.width = "0%";
    seleccion.style.opacity = "0";
    quitarBordes();
}

function quitarBordes() {
    var items = document.getElementsByClassName("item");
    for (var i = 0; i < items.length; i++) {
        items[i].style.border = "none";
    }
}

function addToCart(item) {
    var product_id = item.getAttribute("data-id");
    var product_name = item.getElementsByTagName("p")[0].innerHTML;
    var product_price = item.getElementsByTagName("span")[0].innerHTML.replace("$ ", "");

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "catalogo.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
                alert("Producto añadido al carrito");
            }
        }
    };
    xhr.send("add_to_cart=1&product_id=" + product_id + "&product_name=" + encodeURIComponent(product_name) + "&product_price=" + product_price);
}

function mostrarCarrito() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "catalogo.php", true);
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
    xhr.open("POST", "catalogo.php", true);
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
