<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Shop_management";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Assume customer is logged in and Customer_ID is set
session_start();
if (!isset($_SESSION['customer_id'])) {
    die("Please log in first.");
}
$customer_id = $_SESSION['customer_id'];

$message = "";

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $issue = $conn->real_escape_string($_POST['issue']);

    // Step 1: Find Product_ID and Warranty_period
    $product_query = $conn->query("SELECT Product_ID, Warranty_period FROM product WHERE Name='$product_name'");
    if ($product_query && $product_query->num_rows > 0) {
        $product = $product_query->fetch_assoc();
        $product_id = $product['Product_ID'];
        $warranty_months = $product['Warranty_period'];

        // Step 2: Check if customer ordered this product (via "checks" table)
        $checks_query = $conn->query("SELECT Order_ID FROM checks WHERE Product_ID=$product_id AND Order_ID = (SELECT order_ID FROM `order` WHERE Customer_ID=$customer_id LIMIT 1)");
        if ($checks_query && $checks_query->num_rows > 0) {
            $order_id = $checks_query->fetch_assoc()['Order_ID'];

            // Step 3: Get purchase date from order table
            $order_query = $conn->query("SELECT Date FROM `order` WHERE order_ID=$order_id");
            if ($order_query && $order_query->num_rows > 0) {
                $purchase_date = $order_query->fetch_assoc()['Date'];
                $today = date('Y-m-d');
                $expiry_date = date('Y-m-d', strtotime("+$warranty_months months", strtotime($purchase_date)));

                // Step 4: Check warranty validity
                if ($today <= $expiry_date) {
                    // Still in warranty, allow submission
                    $insert = "INSERT INTO service_request (Product_name, Issue, Request_Date, Customer_ID) VALUES ('$product_name', '$issue', '$today', $customer_id)";
                    if ($conn->query($insert) === TRUE) {
                        $message = "<span style='color:green;'>✅ Service request submitted successfully. Your warranty is valid until $expiry_date.</span>";
                    } else {
                        $message = "<span style='color:red;'>❌ Error submitting request: " . $conn->error . "</span>";
                    }
                } else {
                    $message = "<span style='color:red;'>❌ Warranty expired. (Purchase: $purchase_date, Expires: $expiry_date)</span>";
                }
            } else {
                $message = "<span style='color:red;'>❌ Order not found for this customer.</span>";
            }
        } else {
            $message = "<span style='color:red;'>❌ You did not order this product, so you cannot submit a service request.</span>";
        }
    } else {
        $message = "<span style='color:red;'>❌ Product not found.</span>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Service Request</title>
</head>
<body>
    <h2>Submit a Service Request</h2>
    <form method="post">
        <label>Product Name: <input type="text" name="product_name" required></label><br><br>
        <label>Describe Issue:<br>
        <textarea name="issue" rows="4" cols="40" required></textarea></label><br><br>
        <input type="submit" value="Check Warranty & Submit Request">
    </form>
    <br>
    <?php if (!empty($message)) echo "<div>$message</div>"; ?>
</body>
</html>



