<?php
session_start();
include("admin_area/includes/db.php");

$error = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $submitted_password = mysqli_real_escape_string($con, $_POST['password']);


    $query = "SELECT username, password, email, is_admin FROM users WHERE username = '$username'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);
        $hashed_password_from_db = $user_data['password'];
        $is_admin = $user_data['is_admin'];
        $customer_email = $user_data['email'];

        // VERIFY THE HASHED PASSWORD - THIS IS THE CRITICAL SECURITY FIX
        if (password_verify($submitted_password, $hashed_password_from_db)) {
            // Login successful
            $_SESSION['username'] = $username;
            $_SESSION['customer_email'] = $customer_email; 
            $_SESSION['is_admin'] = $is_admin; 


            if (isset($_SESSION['redirect_to_cart']) && $_SESSION['redirect_to_cart'] === true) {
                unset($_SESSION['redirect_to_cart']);
                header("Location: cart.php"); 
            } else {
                header("Location: index.php"); // Default redirect
            }
            exit(); // Always exit after a header redirect
        } else {
            // Password does not match
            $error = "Invalid username or password.";
        }
    } else {
        // Username not found
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-radius: 5px;
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 22px); padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px;font-size: 16px;
        }

        button {
            background: #333;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            width: 100%;
        }
        button:hover {
            background: #008000; 
        }
        .error {
            color: #d9534f;
            margin: 10px 0;
        }
        .links {
            margin-top: 15px;
            font-size: 14px;
        }
        .links a {
            color: #007bff;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .home-button {
            background: #00008B;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            width: 94%;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-weight:bold;
        }
        .home-button:hover {
            background: #FF0000;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="links">
            <p>Don't have an account? <a href="signup.php">Register here</a></p>
        </div>
        <!--<a href="index.php" class="home-button">Home</a>-->
    </div>
</body>
</html>