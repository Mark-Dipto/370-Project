<?php
session_start();
include 'Partials/_dbconnect[login].php';
if (!isset($_GET['order_id'])) {
    die("Invalid request!");
}

$order_id = (int)$_GET['order_id'];
$order_sql = "SELECT o.Cart_ID, p.Status, p.Online, p.Offline
              FROM `order` o
              JOIN payment p ON o.Payment_ID = p.Payment_ID
              WHERE o.order_ID = $order_id";
$order_result = mysqli_query($conn, $order_sql);
$order = mysqli_fetch_assoc($order_result);

if (!$order) {
    die("Order not found!");
}
$cart_id = $order['Cart_ID'];
$total_sql = "SELECT SUM(p.Price * c.Quantity) AS grand_total
              FROM cart c
              JOIN product_added_to_cart pac ON c.Cart_Id = pac.Cart_Id
              JOIN product p ON pac.Product_ID = p.Product_ID
              WHERE c.Cart_Id = $cart_id";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$grand_total = $total_row['grand_total'];

/* PHP */
$post_data = array();
$post_data['store_id'] = "namch68b1dcae503dc";
$post_data['store_passwd'] = "namch68b1dcae503dc@ssl";
$post_data['total_amount'] = "$grand_total";
$post_data['currency'] = "BDT";
$post_data['tran_id'] = "SSLCZ_TEST_".uniqid();
$post_data['success_url'] = "http://localhost/payment_Success.php";
$post_data['fail_url'] = "http://localhost/new_sslcz_gw/fail.php";
$post_data['cancel_url'] = "http://localhost/new_sslcz_gw/cancel.php";
# $post_data['multi_card_name'] = "mastercard,visacard,amexcard";  # DISABLE TO DISPLAY ALL AVAILABLE

# EMI INFO
$post_data['emi_option'] = "1";
$post_data['emi_max_inst_option'] = "9";
$post_data['emi_selected_inst'] = "9";

# CUSTOMER INFORMATION
$post_data['cus_name'] = "$_SESSION[name]";
$post_data['cus_email'] = "$_SESSION[email]";
$post_data['cus_add1'] = "$_SESSION[address]";
$post_data['cus_add2'] = "Dhaka";
$post_data['cus_city'] = "Dhaka";
$post_data['cus_state'] = "Dhaka";
$post_data['cus_postcode'] = "1000";
$post_data['cus_country'] = "Bangladesh";
$post_data['cus_phone'] = "01711111111";
$post_data['cus_fax'] = "01711111111";

# SHIPMENT INFORMATION
$post_data['ship_name'] = "testnamchvmkh";
$post_data['ship_add1 '] = "Dhaka";
$post_data['ship_add2'] = "Dhaka";
$post_data['ship_city'] = "Dhaka";
$post_data['ship_state'] = "Dhaka";
$post_data['ship_postcode'] = "1000";
$post_data['ship_country'] = "Bangladesh";

# OPTIONAL PARAMETERS
$post_data['value_a'] = "ref001";
$post_data['value_b '] = "ref002";
$post_data['value_c'] = "ref003";
$post_data['value_d'] = "ref004";

# CART PARAMETERS
$post_data['cart'] = json_encode(array(
    array("product"=>"DHK TO BRS AC A1","amount"=>"200.00"),
    array("product"=>"DHK TO BRS AC A2","amount"=>"200.00"),
    array("product"=>"DHK TO BRS AC A3","amount"=>"200.00"),
    array("product"=>"DHK TO BRS AC A4","amount"=>"200.00")
));
$post_data['product_amount'] = "100";
$post_data['vat'] = "5";
$post_data['discount_amount'] = "5";
$post_data['convenience_fee'] = "3";