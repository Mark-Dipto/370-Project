<?php
session_start();
include 'Partials/_dbconnect[login].php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $customer_id = (int)$_POST['customer_id'];
    $payment_type = $_POST['payment_type'];
    $cart_sql = $cart_sql = "SELECT c.Cart_Id, c.Quantity, c.Total_price, 
                p.Product_ID, p.Price, p.Stock_Quantity
                FROM cart c, product_added_to_cart pac, product p
                WHERE c.Cart_Id = pac.Cart_Id 
                AND pac.Product_ID = p.Product_ID";
    $cart_result = mysqli_query($conn, $cart_sql);

    if (mysqli_num_rows($cart_result) == 0) {
        echo "Cart is empty!";
        exit();
    }
    $emp_sql = "SELECT Employee_ID FROM employee LIMIT 1";
    $emp_result = mysqli_query($conn, $emp_sql);
    $emp_row = mysqli_fetch_assoc($emp_result);
    $employee_id = $emp_row['Employee_ID'];
    if ($payment_type == 'Online') {
        $online = 1;
        $offline = 0;
    } else {
        $online = 0;
        $offline = 1;
    }
    $pay_sql = "INSERT INTO payment (Status, Online, Offline, Type) 
                VALUES ('Pending', '$online', '$offline', 0)";
    mysqli_query($conn, $pay_sql);
    $payment_id = mysqli_insert_id($conn);
    $cart_row = mysqli_fetch_assoc($cart_result);
    $cart_id = $cart_row['Cart_Id'];
    $date = date('Y-m-d');
    $order_sql = "INSERT INTO `order` (Date, Employee_ID, Payment_ID, Cart_ID)
                  VALUES ('$date', '$employee_id', '$payment_id', '$cart_id')";
    mysqli_query($conn, $order_sql);
    $order_id = mysqli_insert_id($conn);
    mysqli_data_seek($cart_result, 0);
    while ($item = mysqli_fetch_assoc($cart_result)) {
        $product_id = $item['Product_ID'];
        $quantity = $item['Quantity'];
        $update_stock = "UPDATE product SET Stock_Quantity = Stock_Quantity - $quantity 
                         WHERE Product_ID = $product_id";
        mysqli_query($conn, $update_stock);
    }
    $clear_cart = "DELETE FROM cart";
    mysqli_query($conn, $clear_cart);
    if ($payment_type == 'Online') {
        echo "Redirecting to SSLCommerz payment page...";
        header("Location: ssl_comerz.php?order_id=$order_id");
        exit();
    } else {
        echo "Order placed successfully with Order ID: $order_id (Cash on Delivery)";
    }
}
?>


