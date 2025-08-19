<?php
session_start();

// Get product ID from URL
$product_id = $_GET['id'];

// Remove product from cart
if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}

// Go back to cart
header("Location: Cart.php");
?>
