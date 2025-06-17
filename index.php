<?php
session_start();
include("functions/functions.php");
add_to_cart();

?>
<!DOCTYPE html>
<html>
<head>
    <title>My Online Shop</title>
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
        </header> 
        <section class="content_wrapper"> <div id="sidebar">
                <div class="sidebar_section">
                    <h3>Search Products</h3>
                    <form method="get" action="#" enctype="multipart/form-data" class="search-form">
                        <input type="text" name="user_query" placeholder="Search a Product..." required>
                        <button type="submit" name="search" value="Search"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                    <!--This section is for development -->
                <div class="sidebar_section promo_section">
                    <h3><i class="fas fa-tags"></i> Special Offers</h3>
                    <ul class="sidebar_list">
                        <li><a href="#">Daily Deals <span class="badge hot">HOT!</span></a></li>
                        <li><a href="#">Clearance Sale</a></li>
                        <li><a href="#">New Arrivals</a></li>
                        <li><a href="#">Gift Cards</a></li>
                    </ul>
                </div>
                    <!--This section is for development -->
                <div class="sidebar_section customer_service">
                    <h3><i class="fas fa-headset"></i> Need Help?</h3>
                    <ul class="sidebar_list">
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Returns Policy</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
            </div><div id="content_area"> <div id="shopping_cart">
                    <div class="welcome_message">
                        <span>Welcome mga <b style="color:#ffc107;">SUKI!</b></span> </div>
                    <div class="cart_summary">
                        <span>Total Items: <b><?php total_items(); ?></b></span>
                        <span>Total Price: <b><?php total_price(); ?></b></span>
                        <a href="cart.php" class="go_to_cart_btn"><i class="fas fa-shopping-cart"></i> Go to Cart</a>
                    </div>
                </div><div id="products_box">
                    <?php
                    getPro();
                    getCatPro();
                    getBrandPro();
                    ?>
                </div></div></section><footer id="footer">
            <p>&copy; <?php echo date("Y"); ?> My Online Shop. All Rights Reserved.</p>
            <p>Designed by Ronelio</p>
        </footer>
    </div></body>
</html>
