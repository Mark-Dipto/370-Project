<?php
session_start();
$login = false;
$showError = false;

if($_SERVER['REQUEST_METHOD'] == "POST"){
    include 'Partials/_dbconnect[login].php';
    $password = $_POST["password"];
    $email = $_POST["email"];

    if($email == "" || $password == ""){
        $showError = "Please fill all the fields";
    }else{
        $sql = "SELECT * FROM customer WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if($row){
            $login = true;
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            $_SESSION['address'] = $row['address'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['customer_id'] = $row['customer_id'];
            header("Location: product_list.php");
            exit();
        }else{
            $showError = "Invalid Credentials";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

<style>
  body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  form {
    background: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
  }

  input[type="email"],
  input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }

  .form-text {
    font-size: 0.9em;
    color: #6c757d;
  }

  .btn {
    background-color: #007bff;
    color: white;
    padding: 10px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
  }

  .btn:hover {
    background-color: #0056b3;
  }

  .alert {
    margin-top: 20px;
    padding: 15px;
    border-radius: 4px;
    color: white;
    background-color: #dc3545;
  }
</style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
</head>
<body>
    <form method="POST">
      <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control" id="exampleInputPassword1" name="password">
        <small class="form-text text-muted">We'll never share your password with anyone else.</small>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>

<?php
    if($showError) {
        echo '<div class="alert alert-danger" role="alert">' . $showError . '</div>';
    }
?>
</body>
</html>
<?php
