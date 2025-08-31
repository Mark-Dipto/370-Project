<?php
include 'Partials/_dbconnect[login].php';
$check_cart = "SELECT COUNT(*) as count FROM cart";
$cart_result = mysqli_query($conn, $check_cart);
$cart_count = mysqli_fetch_assoc($cart_result)['count'];
if ($cart_count == 0) {
    echo "<h2>Your cart is empty</h2>";
    echo "<a href='product_list.php' class='btn btn-primary'>Go Shopping</a>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Your Shopping Cart</h2>       
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
        $sql = "SELECT c.Cart_Id, c.Quantity, c.Total_price, 
               p.Name, p.Price, p.Stock_Quantity
               FROM cart c, product_added_to_cart pac, product p
               WHERE c.Cart_Id = pac.Cart_Id 
               AND pac.Product_ID = p.Product_ID";
               $result = mysqli_query($conn, $sql);
               $grand_total = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $line_total = ((float)$row['Price']) * ((int)$row['Quantity']);
            $grand_total += $line_total;
        ?>
            <tr>
                <td><?php echo $row['Name']; ?></td>
                <td>৳<?php echo $row['Price']; ?></td>
                <td><?php echo $row['Quantity']; ?></td>
                <td>
                <?php $item_total = $row['Price'] * $row['Quantity'];
                echo "৳" . number_format($item_total, 2); ?>
</td>
                <td>
                    <?php if($row['Stock_Quantity'] < $row['Quantity']) { ?>
                        <span class="text-danger d-block mb-1">Out of Stock</span>
                    <?php } ?>
                    <a href="remove_from_cart.php?id=<?php echo $row['Cart_Id']; ?>" class="btn btn-danger btn-sm">Remove</a>
                </td>
            </tr>
        <?php } ?>

            <tr class="table-info">
                <td colspan="3"><strong>Grand Total:</strong></td>
                <td><strong>৳<?php echo $grand_total; ?></strong></td>
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

