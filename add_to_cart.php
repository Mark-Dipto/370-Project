<?php
include 'Partials/_dbconnect[login].php';

if (isset($_POST['add'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity   = (int)$_POST['quantity'];
    $sql = "SELECT Stock_Quantity, Price FROM product WHERE Product_ID = '$product_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        die("Product not found!");
    }
    $stock = (int)$row['Stock_Quantity'];
    $price = (float)$row['Price'];
    if ($quantity > $stock) {
        echo " You cannot add more than available stock ($stock items). ";
        echo '<a href="Cart.php" class="btn btn-sm btn-secondary">Back to Cart</a>';
        exit();
    }
    $check_sql = "SELECT c.Cart_Id, c.Quantity 
              FROM cart c, product_added_to_cart pac 
              WHERE c.Cart_Id = pac.Cart_Id 
              AND pac.Product_ID = '$product_id'";

    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $cart_row = mysqli_fetch_assoc($check_result);
        $new_quantity = (int)$cart_row['Quantity'] + $quantity;
        if ($new_quantity > $stock) {
            echo " You already have {$cart_row['Quantity']} in cart. Max you can add: " . ($stock - (int)$cart_row['Quantity']) . ". ";
            echo '<a href="remove_from_cart.php?id=' . $cart_row['Cart_Id'] . '" class="btn btn-sm btn-danger">Remove from Cart</a> ';
            echo '<a href="Cart.php" class="btn btn-sm btn-secondary">Back to Cart</a>';
            exit();
        }

        $total_price = $new_quantity * $price;
        $update_sql = "UPDATE cart SET Quantity='$new_quantity', Total_price='$total_price' 
                       WHERE Cart_Id='{$cart_row['Cart_Id']}'";
        mysqli_query($conn, $update_sql);
    } else {
        $total_price = $quantity * $price;
        $insert_sql = "INSERT INTO cart (Quantity, Total_price) 
                       VALUES ('$quantity', '$total_price')";
        mysqli_query($conn, $insert_sql);
        $new_cart_id = mysqli_insert_id($conn);
        $link_sql = "INSERT INTO product_added_to_cart (Cart_Id, Product_ID) 
                     VALUES ('$new_cart_id', '$product_id')";
        mysqli_query($conn, $link_sql);
    }
    header("Location: Cart.php");
    exit();
}
?>



