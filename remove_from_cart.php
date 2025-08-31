<?php
include 'Partials/_dbconnect[login].php';
if (isset($_GET['id'])) {
    $cart_id = (int) $_GET['id'];
    $sql = "DELETE FROM product_added_to_cart WHERE Cart_Id = $cart_id";
    mysqli_query($conn, $sql);
    $sql2 = "DELETE FROM cart WHERE Cart_Id = $cart_id";
    mysqli_query($conn, $sql2);
    header("Location: Cart.php");
    exit();
}
?>
