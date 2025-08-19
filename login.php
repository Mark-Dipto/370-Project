<?php
session_start();
$login = false;
$showError = false;

if($_SERVER['REQUEST_METHOD']=="POST"){
    include 'Partials/_dbconnect[login].php';
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    if($username == "" || $email == "" || $password == ""){
        $showError = "Please fill all the fields";
    } else {
        $sql = "SELECT * FROM customer WHERE name='$username' AND email='$email'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);

        if($row){
            if(($password == $row['password'])){//If we don't use password_hash then password_verify() will not work
                $login = true;
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
            } else {
                $showError = "Invalid Credentials";
            }
        } else {
            $showError = "Invalid Credentials";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
<!-- Some basic css for styling -->
        <style>
        body {
            background: #f5f6fa;
            font-family: Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 60px auto;
            padding: 30px 25px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            font-weight: bold;
        }
        .btn-primary {
            width: 100%;
            background: #007bff;
            border: none;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .alert {
            margin-top: 20px;
            padding: 12px;
            border-radius: 4px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
<!-- End of basic css for styling -->
</head>
<body>
    <form method="POST">
      <div class="form-group">
        <label for="username">Name</label>
        <input type="text" class="form-control" id="username" name="username">
        <small class="form-text text-muted"></small>
      </div>
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
    if($login){
        header("Location: product_list.php");
    } else if($showError) {
        echo '<div class="alert alert-danger" role="alert">' . $showError . '</div>';
    }
?>
</body>
</html>
<?php
