<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['cart'])) {
        $cartData = [];
        $totalQuantity = array_sum($_SESSION['cart']);
        $cartData['totalQuantity'] = $totalQuantity;
        $cartData['cartDetails'] = $_SESSION['cart'];
        echo json_encode($cartData);
    } else {
        echo json_encode(['totalQuantity' => 0, 'cartDetails' => []]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    $cartData = [];
    $totalQuantity = array_sum($_SESSION['cart']);
    $cartData['totalQuantity'] = $totalQuantity;
    $cartData['cartDetails'] = $_SESSION['cart'];

    echo json_encode($cartData);
}
?>