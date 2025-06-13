<?php
// Database connection
$con = mysqli_connect("localhost", "root", "P@$$w0rd", "ecommerce_website");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Function to get IP Address
if (!function_exists('getIp')) {
    function getIp() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($ip == "::1") {
            $ip = "127.0.0.1"; // For localhost
        }
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $ip;
    }
}

// Getting categories
if (!function_exists('getCats')) {
    function getCats() {
        global $con;
        $get_cats = "SELECT * FROM categories";
        $run_cats = mysqli_query($con, $get_cats);
        while ($row_cats = mysqli_fetch_array($run_cats)) {
            $cat_id = $row_cats['cat_id'];
            $cat_title = $row_cats['cat_title'];
            echo "<li><a href='index.php?cat=$cat_id'>$cat_title</a></li>";
        }
    }
}

// Getting brands
if (!function_exists('getBrands')) {
    function getBrands() {
        global $con;
        $get_brands = "SELECT * FROM brands";
        $run_brands = mysqli_query($con, $get_brands);
        while ($row_brands = mysqli_fetch_array($run_brands)) {
            $brand_id = $row_brands['brand_id'];
            $brand_title = $row_brands['brand_title'];
            echo "<li><a href='index.php?brand=$brand_id'>$brand_title</a></li>";
        }
    }
}

// Getting products
if (!function_exists('getPro')) {
    function getPro() {
        if (!isset($_GET['cat']) && !isset($_GET['brand'])) {
            global $con;
            $get_pro = "SELECT * FROM product ORDER BY product_id ASC LIMIT 0,50";
            $run_pro = mysqli_query($con, $get_pro);
            while ($row_pro = mysqli_fetch_array($run_pro)) {
                $pro_id = $row_pro['Product_id'];
                $pro_title = $row_pro['Product_title'];
                $pro_price = number_format($row_pro['Product_price']);
                $pro_image = $row_pro['Product_image'];
                echo "
                <div class='single_product'>
                    <div class='product_image_container'>
                        <img src='admin_area/product_images/$pro_image' width='180' height='180'/>
                        <a href='index.php?add_cart=$pro_id' class='add_to_cart_btn'>Add to Cart</a>
                    </div>
                    <h3>$pro_title</h3>
                    <p><b>₱ $pro_price</b></p>
                    <a href='details.php?pro_id=$pro_id' class='details_link'>Details</a>
                </div>
                ";
            }
        }
    }
}

// Getting products by category
if (!function_exists('getCatPro')) {
    function getCatPro() {
        if (isset($_GET['cat'])) {
            $cat_id = $_GET['cat'];
            global $con;
            $get_cat_pro = "SELECT * FROM product WHERE product_cat='$cat_id'";
            $run_cat_pro = mysqli_query($con, $get_cat_pro);
            $count_cats = mysqli_num_rows($run_cat_pro);
            if ($count_cats == 0) {
                echo "<h2 style='padding:20px;'>There is no product in this category!</h2>";
            }
            while ($row_cat_pro = mysqli_fetch_array($run_cat_pro)) {
                $pro_id = $row_cat_pro['Product_id'];
                $pro_title = $row_cat_pro['Product_title'];
                $pro_price = number_format($row_cat_pro['Product_price']);
                $pro_image = $row_cat_pro['Product_image'];
                echo "
                <div class='single_product'>
                    <div class='product_image_container'>
                        <img src='admin_area/product_images/$pro_image' width='180' height='180'/>
                        <a href='index.php?add_cart=$pro_id' class='add_to_cart_btn'>Add to Cart</a>
                    </div>
					<h3>$pro_title</h3>
                    <p><b> ₱ $pro_price</b></p>
                    <a href='details.php?pro_id=$pro_id' class='details_link'>Details</a>
                </div>
                ";
            }
        }
    }
}

// Getting products by brand
if (!function_exists('getBrandPro')) {
    function getBrandPro() {
        if (isset($_GET['brand'])) {
            $brand_id = $_GET['brand'];
            global $con;
            $get_brand_pro = "SELECT * FROM product WHERE product_brand='$brand_id'";
            $run_brand_pro = mysqli_query($con, $get_brand_pro);
            $count_brands = mysqli_num_rows($run_brand_pro);
            if ($count_brands == 0) {
                echo "<h2 style='padding:20px;'>There is no product in this brand!</h2>";
            }
            while ($row_brand_pro = mysqli_fetch_array($run_brand_pro)) {
                $pro_id = $row_brand_pro['Product_id'];
                $pro_title = $row_brand_pro['Product_title'];
                $pro_price = number_format($row_brand_pro['Product_price']);
                $pro_image = $row_brand_pro['Product_image'];
                echo "
                <div class='single_product'>
                    <div class='product_image_container'>
                        <img src='admin_area/product_images/$pro_image' width='180' height='180'/>
                        <a href='index.php?add_cart=$pro_id' class='add_to_cart_btn'>Add to Cart</a>
                    </div>
					<h3>$pro_title</h3>
                    <p><b> ₱ $pro_price</b></p>
                    <a href='details.php?pro_id=$pro_id' class='details_link'>Details</a>
                </div>
                ";
            }
        }
    }
}
// Adding to cart
if (!function_exists('add_to_cart')) {
    function add_to_cart() {
        global $con;

        // Ensure the database connection is established
        if (!$con) {
            die("<script>console.log('Database connection failed: " . mysqli_connect_error() . "');</script>");
        }

        if (isset($_GET['add_cart'])) {
            $ip = getIp();
            $pro_id = $_GET['add_cart'];

            if (!empty($pro_id)) {
                // Check if product is already in cart
                $check_pro = "SELECT * FROM cart WHERE ip_address='$ip' AND product_id='$pro_id'";
                $run_check = mysqli_query($con, $check_pro);

                if (mysqli_num_rows($run_check) > 0) {
                    // Update quantity if product exists
                    $update_qty = "UPDATE cart SET qty = qty + 1 WHERE ip_address='$ip' AND product_id='$pro_id'";
                    mysqli_query($con, $update_qty);
                } else {
                    // Insert product if it doesn't exist
                    $insert_pro = "INSERT INTO cart (product_id, ip_address, qty) VALUES ('$pro_id', '$ip', 1)";
                    mysqli_query($con, $insert_pro);
                }
            }
        }
    }
}

// Total items in cart
if (!function_exists('total_items')) {
    function total_items() {
        global $con;
        $ip = getIp();
        $get_items = "SELECT SUM(qty) AS total_qty FROM cart WHERE ip_address='$ip'";
        $run_items = mysqli_query($con, $get_items);
        $result = mysqli_fetch_assoc($run_items);
        $count_items = $result['total_qty'];
        echo $count_items;
    }
}

// Total price of items in cart
if (!function_exists('total_price')) {
    function total_price() {
        global $con;
        $ip = getIp();
        $total = 0;
        $sel_price = "SELECT product_id, qty FROM cart WHERE ip_address='$ip'";
        $run_price = mysqli_query($con, $sel_price);
        while ($p_price = mysqli_fetch_array($run_price)) {
            $pro_id = $p_price['product_id'];
            $qty = $p_price['qty'];
            $pro_price = "SELECT Product_price FROM product WHERE Product_id='$pro_id'";
            $run_pro_price = mysqli_query($con, $pro_price);
            while ($pp_price = mysqli_fetch_array($run_pro_price)) {
                $product_price = $pp_price['Product_price'];
                $total += $product_price * $qty;
            }
        }
        echo "₱" . number_format($total, 2);
    }
}

// Remove item from cart
if (isset($_POST['remove'])) {
    $remove_id = $_POST['remove_id'];
    $delete_query = "DELETE FROM cart WHERE product_id='$remove_id' AND ip_address='" . getIp() . "'";
    $run_delete = mysqli_query($con, $delete_query);

    if ($run_delete) {
        echo "<script>window.open('cart.php', '_self')</script>";
    }
}
?>
<style>
.single_product {
    position: relative;
    text-align: center;
}

.product_image_container {
    position: relative;
    display: inline-block;
}

.product_image_container img {
    width: 180px;
    height: 180px;
}

.add_to_cart_btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: none;
    background: #333;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
    font-weight: bold;
}

.product_image_container:hover .add_to_cart_btn {
    display: block;
}
</style>
