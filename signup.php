<?php
session_start();
include("admin_area/includes/db.php");
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Basic validation: Check if passwords match
    if ($password === $confirm_password) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the username already exists in the database
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) == 0) {
            // Username is unique, proceed with insertion
            $insert_query = "INSERT INTO users (name, address, email, phone, username, password) VALUES ('$name', '$address', '$email', '$phone', '$username', '$hashed_password')";
            
            if (mysqli_query($con, $insert_query)) {
                // Registration successful!
                // Automatically log the user in by setting session variables
                $_SESSION['customer_email'] = $email; 
                $_SESSION['customer_username'] = $username;

                // Redirect to the checkout page directly
                header("Location: checkout.php");
                exit();
            } else {
                // Database insertion error
                $error = "Error: " . mysqli_error($con);
            }
        } else {
            // Username already exists
            $error = "Username already exists. Please choose another one.";
        }
    } else {
        // Passwords do not match
        $error = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
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
        .signup-container {
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
        input[type="text"], input[type="password"], input[type="email"], input[type="tel"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #333;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight:bold;
            border-radius: 5px;
            width: 100%;
        }
        button:hover {
            background: #FF0000;
        }
        .error {
            color: #d9534f;
            margin: 10px 0;
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
        }
        .home-button:hover {
            background: #FF0000;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="signup.php" method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <a href="index.php" class="home-button">Home</a>
    </div>
</body>
</html>