<?php
session_start();
include("functions/functions.php");
add_to_cart();

?>
<!DOCTYPE html>
<html>
<head>
    <title>My Shopping Cart</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css" media="all" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="main_wrapper">
        <header class="header_wrapper">
            <div class="logo">
                <a href="index.php"><img src="images/logo.png" alt="My Online Shop Logo"></a>
            </div>
            <nav class="menubar">
                <ul id="menu">
                    <li><a href="index.php">Home</a></li>
                    <li class="dropdown">
                        <a href="#">Categories <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-menu"><?php getCats(); ?></ul>
                    </li>
                    <li class="dropdown">
                        <a href="#">Brands <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-menu"><?php getBrands(); ?></ul>
                    </li>

                    <?php
                    // Display 'Input Products' link only for admin users
                    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
                        echo '<li><a href="admin_area/insert_product.php">Input Products</a></li>';
                    }
                    ?>
                </ul>
                <ul id="auth_menu"> <?php
                    // Display Logout if logged in, otherwise Login
                    if (isset($_SESSION['customer_email'])) {
                        echo '<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>';
                    } else {
                        echo '<li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </header> <section class="content_wrapper">
            <div id="sidebar">
                <div class="sidebar_section">
                    <h3>Search Products</h3>
                    <form method="get" action="#" enctype="multipart/form-data" class="search-form">
                        <input type="text" name="user_query" placeholder="Search for products..." required>
                        <button type="submit" name="search" value="Search"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="sidebar_section">
                    <h3>Quick Links</h3>
                    <ul class="sidebar_list">
                        <li><a href="index.php"><i class="fas fa-arrow-left"></i> Continue Shopping</a></li>
                        <!--<li><a href="my_account.php"><i class="fas fa-user-circle"></i> My Account</a></li>
                        <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact Support</a></li>-->
                    </ul>
                </div>
            </div><div id="content_area">
                <div id="shopping_cart">
                    <div class="welcome_message">
                        <span>Your Cart: <b style="color:#ffc107;">Review Items!</b></span>
                    </div>
                    <div class="cart_summary">
                        <span>Total Items: <b><?php total_items(); ?></b></span>
                        <span>Total Price: <b><?php total_price(); ?></b></span>

                        <?php
                        // --- Logic for the "Checkout" or "Register/Login to Checkout" link ---
                        if (isset($_SESSION['customer_email'])) {
                            echo "<a href='checkout.php' class='go_to_cart_btn'><i class='fas fa-cash-register'></i> Proceed to Checkout</a>";
                        } else {
                            // User is NOT logged in, direct to signup/registration page
                            echo "<a href='signup.php' class='go_to_cart_btn'><i class='fas fa-user-plus'></i> Register/Login to Checkout</a>";
                        }
                        ?>
                    </div>
                </div> <div id="products_box">
                    <?php
                    if (!isset($con)) {
                        $con = mysqli_connect("localhost", "root", "", "ecommerce_db"); // Replace 'ecommerce_db' with your actual database name
                        if (mysqli_connect_errno()) {
                            echo "<p>Failed to connect to MySQL: " . mysqli_connect_error() . "</p>";
                            exit();
                        }
                    }

                    $ip = getIp(); // Ensure getIp() is defined in functions.php and returns the user's IP.

                    // Check if a product needs to be removed
                    if (isset($_POST['remove'])) {
                        $remove_id = $_POST['remove_id'];
                        $delete_product = "DELETE FROM cart WHERE product_id = '$remove_id' AND ip_address = '$ip'";
                        $run_delete = mysqli_query($con, $delete_product);

                        if ($run_delete) {
                            echo "<script>alert('Product removed from cart!');</script>";
                            echo "<script>window.open('cart.php','_self');</script>"; // Refresh the page
                        } else {
                            echo "<p>Error removing product: " . mysqli_error($con) . "</p>";
                        }
                    }

                    // Query to fetch items currently in the cart for the current IP
                    $sel_cart = "SELECT * FROM cart WHERE ip_address='$ip'";
                    $run_cart = mysqli_query($con, $sel_cart);

                    if ($run_cart === FALSE) {
                        echo "<p>Error retrieving cart items: " . mysqli_error($con) . "</p>";
                    } elseif (mysqli_num_rows($run_cart) == 0) {
                        echo "<p style='text-align: center; padding: 50px; font-size: 1.2em; color: #555;'>Your shopping cart is currently empty. <br><a href='index.php' style='color:#007bff; text-decoration: underline;'>Continue Shopping!</a></p>";
                    } else {
                        // Loop through cart items and display them
                        while ($cart_row = mysqli_fetch_array($run_cart)) {
                            $pro_id = $cart_row['product_id'];
                            $pro_qty = $cart_row['qty'];

                            $pro_query = "SELECT * FROM product WHERE Product_id='$pro_id'";
                            $run_pro_query = mysqli_query($con, $pro_query);

                            if ($run_pro_query === FALSE) {
                                echo "<p>Error retrieving product details for ID " . htmlspecialchars($pro_id) . ": " . mysqli_error($con) . "</p>";
                            } else {
                                while ($pro_row = mysqli_fetch_array($run_pro_query)) {
                                    $pro_title = $pro_row['Product_title'];
                                    $pro_price = $pro_row['Product_price'];
                                    $pro_image = $pro_row['Product_image'];

                                    echo "
                                    <div class='single_product'>
                                        <div class='product_image_container'>
                                            <img src='admin_area/product_images/$pro_image' alt='$pro_title'/>
                                        </div>
                                        <h3>$pro_title</h3>
                                        <p><b>₱ " . number_format($pro_price, 2) . "</b></p>
                                        <p>Quantity: $pro_qty</p>
                                        <form action='cart.php' method='post'>
                                            <input type='hidden' name='remove_id' value='$pro_id'>
                                            <button type='submit' name='remove' class='details_link' style='background-color: #dc3545;'>
                                                <i class='fas fa-trash-alt'></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                    ";
                                }
                            }
                        }
                    }
                    ?>
                </div> </div> </section> <footer id="footer">
            <p>&copy; <?php echo date("Y"); ?> My Online Shop. All Rights Reserved.</p>
            <p>Designed by Ronelio</p>
        </footer>
    </div></body>
</html>