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
</head>
<style>
  body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding-top: 30px;
  }

  h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #343a40;
  }

  .container {
    max-width: 1200px;
    margin: auto;
  }

  .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
  }

  .card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #007bff;
  }

  .card-text {
    font-size: 0.95rem;
    color: #6c757d;
  }

  .list-group-item {
    font-size: 0.9rem;
    background-color: #fdfdfd;
  }

  form {
    margin-top: 10px;
  }

  .form-control {
    height: 38px;
    font-size: 0.9rem;
  }

  .btn-primary {
    font-size: 0.9rem;
    padding: 8px 12px;
  }

  .btn-outline-primary,
  .btn-outline-success {
    margin-right: 10px;
  }

  .mb-4 {
    text-align: center;
  }

  @media (max-width: 768px) {
    .card {
      width: 100% !important;
    }

    .form-control {
      width: 100% !important;
    }

    .d-flex {
      flex-direction: column;
      align-items: stretch;
    }

    .btn-primary {
      width: 100%;
      margin-top: 10px;
    }
  }
</style>
<body>
        <div class="container">
            <h2>Product List</h2>
            <div class="mb-4">
                <a href="Cart.php" class="btn btn-outline-primary">View Cart</a>
            </div>
        </div>
        <div class="row">
            <?php
            // If we use while loop before the html body then though it can fetch all the data but it can store only the last data. That's why we use it here
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['Product_ID'];
                $name = $row['Name'];
                $price = $row['Price'];
                $stockQuantity = $row['Stock_Quantity'];
                $warranty = $row['Warranty_period'];
                $brand = $row['Brand'];
            ?>
            <div class="col-md-4 mb-4">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo ($name); ?></h5>
                        <p class="card-text">Very Good Product</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Price:৳ <?php echo ($price); ?></li>
                        <li class="list-group-item">Stock: <?php echo ($stockQuantity); ?></li>
                        <li class="list-group-item">Warranty: <?php echo ($warranty); ?></li>
                        <li class="list-group-item">Brand: <?php echo ($brand); ?></li>
                    </ul>
                </div>
                <form method="POST" action="add_to_cart.php" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="product_id" value="<?php echo (int)$id;?>">
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo (int)$stockQuantity;?>" class="form-control" style="width: 80px;">
                    <button type="submit" name="add" value="1" class="card-link btn btn-primary">Add to Cart</button>
                </form>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>