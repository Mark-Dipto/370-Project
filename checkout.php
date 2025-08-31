<?php
include 'Partials/_dbconnect[login].php';
$sql = "SELECT c.Cart_Id, c.Quantity, c.Total_price, 
p.Name, p.Brand, p.Price, p.Stock_Quantity
FROM cart c, product_added_to_cart pac, product p
WHERE c.Cart_Id = pac.Cart_Id 
AND pac.Product_ID = p.Product_ID";
$result = mysqli_query($conn, $sql);
$cart_items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
}
if (count($cart_items) == 0) {
    echo "<div class='container mt-5 text-center'>";
    echo "<h2>Your cart is empty</h2>";
    echo "<a href='product_list.php' class='btn btn-primary mt-3'>Go Shopping</a>";
    echo "</div>";
    exit();
}
$grand_total = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Checkout</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item) { 
                $line_total = ((float)$item['Price']) * ((int)$item['Quantity']);
                $grand_total += $line_total;
            ?>
            <tr>
                <td><?php echo $item['Name']; ?></td>
                <td><?php echo $item['Brand']; ?></td>
                <td>৳<?php echo $item['Price']; ?></td>
                <td><?php echo $item['Quantity']; ?></td>
                <td>৳<?php echo $line_total; ?></td>
            </tr>
            <?php } ?>
            <tr class="table-info">
                <td colspan="4"><strong>Grand Total:</strong></td>
                <td><strong>৳<?php echo $grand_total; ?></strong></td>
            </tr>
        </tbody>
    </table>

    <form method="POST" action="checkout[order_creation].php">
        <input type="hidden" name="customer_id" value="<?php echo (int)$_SESSION['customer_id']; ?>">
        <div class="mb-3">
            <label for="payment_type" class="form-label">Payment Method</label>
            <select id="payment_type" name="payment_type" class="form-select">
                <option value="COD">Cash on Delivery</option>
                <option value="Online">Online (SSLCommerz)</option>
            </select>
        </div>
        <button type="submit" name="checkout" class="btn btn-success">Place Order</button>
        <a href="Cart.php" class="btn btn-secondary">Back to Cart</a>
    </form>
</div>
</body>
</html>
