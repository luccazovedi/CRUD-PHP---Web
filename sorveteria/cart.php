<?php
session_start();

// Verifica se o carrinho existe na sessão
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        echo "Product ID: $product_id - Quantity: $quantity <br>";
    }
} else {
    echo "Carrinho vazio";
}
?>
