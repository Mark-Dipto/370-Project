<?php
session_start();

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "shop");

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<h2>Your cart is empty</h2>";
    echo "<a href='product_list.php' class='btn btn-primary'>Go Shopping</a>";
    echo "</div>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .cart-container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .cart-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .table th {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }
        .btn-container {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h2 class="cart-title"> Your Shopping Cart</h2>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    // Get product info from database
                    $sql = "SELECT name, price FROM product WHERE id = $product_id";
                    $result = mysqli_query($conn, $sql);
                    $product = mysqli_fetch_assoc($result);
                    
                    $name = $product['name'];
                    $price = $product['price'];
                    $total = $price * $quantity;
                    $grand_total += $total;
                    ?>
                    
                    <tr>
                        <td><?php echo $name; ?></td>
                        <td>$<?php echo $price; ?></td>
                        <td><?php echo $quantity; ?></td>
                        <td>$<?php echo $total; ?></td>
                        <td><a href="remove_from_cart.php?id=<?php echo $product_id; ?>" class="btn btn-danger btn-sm">Remove</a></td>
                    </tr>
                <?php } ?>
                
                <tr class="table-info">
                    <td colspan="3"><strong>Grand Total:</strong></td>
                    <td><strong>$<?php echo $grand_total; ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        
        <div class="mt-3">
            <a href="product_list.php" class="btn btn-primary">Continue Shopping</a>
            <a href="checkout.php" class="btn btn-success">Checkout</a>
        </div>
    </div>
</body>
</html>
