<?php
session_start(); 

// Include your database connection and functions file
include("admin_area/includes/db.php"); 
include("functions/functions.php"); 

// Check if a customer was logged in (optional, but good practice)
if (isset($_SESSION['customer_email'])) {
    $ip = getIp(); 

    // Delete cart items associated with this IP address
    $delete_cart_query = "DELETE FROM cart WHERE ip_address = ?";
    $stmt = mysqli_prepare($con, $delete_cart_query);
    mysqli_stmt_bind_param($stmt, "s", $ip); // 's' for string
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
      
    } else {
     
    }
    mysqli_stmt_close($stmt);

    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Optional: Also clear the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Redirect to home page or login page after logout
    header("Location: index.php");
    exit();

} else {
    // If not logged in, just redirect to home page
    header("Location: index.php");
    exit();
}
?>