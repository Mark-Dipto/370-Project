<?php
include 'Partials/_dbconnect[login].php';
$sql = "SELECT * FROM product";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "There is a error: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Basic CSS for styling -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0,0,0,0.15);
        }
        .card img {
            height: 200px;
            object-fit: cover;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }
        .list-group-item {
            font-size: 0.95rem;
        }
        .card-body .card-link {
            display: inline-block;
            background-color: #0d6efd;
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .card-body .card-link:hover {
            background-color: #0b5ed7;
        }
    </style>
    <!-- End of basic css for styling -->
</head>
<body>
    <div class="container">
            <h2>Product List</h2>
            <a href="Cart.php" class="btn btn-outline-primary">View Cart</a>
        </div>
        <div class="row">
            <?php
            // If we use while loop before the html body then though it can fetch all the data but it can store only the last data. That's why we use it here
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['id'];
                $name = $row['name'];
                $price = $row['price'];
                $stockQuantity = $row['quantity'];
                $warranty = $row['warranty'];
                $brand = $row['brand'];
            ?>
            <div class="col-md-4 mb-4">
                <div class="card" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="<?php echo htmlspecialchars($name); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($name); ?></h5>
                        <p class="card-text">Very Good Product</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Price: $<?php echo ($price); ?></li>
                        <li class="list-group-item">Stock: <?php echo ($stockQuantity); ?></li>
                        <li class="list-group-item">Warranty: <?php echo ($warranty); ?></li>
                        <li class="list-group-item">Brand: <?php echo ($brand); ?></li>
                    </ul>
                    <div class="card-body">
                        <form method="POST" action="add_to_cart.php" class="d-flex align-items-center gap-2">
                            <input type="hidden" name="id" value="<?php echo (int)$id; ?>">
                            <input type="number" name="quantity" min="1" max="<?php echo (int)$stockQuantity; ?>" value="1" class="form-control" style="width: 70px;">
                            <button type="submit" name="add" value="1" class="card-link btn btn-primary">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>