<?php
session_start();

// Connect to database
include 'Partials/_dbconnect[login].php';

// Create cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Get product ID from form
$product_id = $_POST['id'];
$quantity = $_POST['quantity'];

// Add to cart
if (isset($_SESSION['cart'][$product_id])) {
    // Product already in cart - add more
    $_SESSION['cart'][$product_id] += $quantity;
} else {
    // New product - add to cart
    $_SESSION['cart'][$product_id] = $quantity;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Added to Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 text-center">
        <div class="alert alert-success">
            <h3>✅ Product Added!</h3>
            <p>Your product has been added to the cart.</p>
            <a href="Cart.php" class="btn btn-primary">View Cart</a>
            <a href="product_list.php" class="btn btn-secondary">Continue Shopping</a>
        </div>
    </div>
</body>
</html>


