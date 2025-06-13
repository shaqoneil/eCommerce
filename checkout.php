<?php
include("admin_area/includes/db.php");
include("functions/functions.php");

session_start(); 

$ip = getIp(); 
$total = 0; 

// Fetch cart items for the current user
$get_cart_items = "SELECT * FROM cart WHERE ip_address='$ip'";
$run_cart_items = mysqli_query($con, $get_cart_items);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            padding: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total {
            text-align: right;
            padding-right: 20px;
            font-weight: bold;
        }
        .total-value {
            color: #d9534f;
        }
        form {
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 20px;
            border-radius: 5px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input, select {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #333;
            color: #fff;
            border: none;
            padding: 15px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            display: inline-block;
			font-weight:bold;
        }
        button:hover {
            background: #FF0000;
        }
        .home-button, .go-back-button {
            background: #00008B;
            color: #fff;
            border: none;
            padding: 15px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
            text-decoration: none;
        }
        .home-button:hover, .go-back-button:hover {
            background: #FF0000;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
			font-weight:bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($cart_item = mysqli_fetch_assoc($run_cart_items)) {
                    $product_id = $cart_item['product_id'];
                    $qty = $cart_item['qty'];

                    // Fetch product details
                    $get_product = "SELECT * FROM product WHERE Product_id='$product_id'";
                    $run_product = mysqli_query($con, $get_product);
                    $product = mysqli_fetch_assoc($run_product);

                    $product_price = $product['Product_price'];
                    $product_name = $product['Product_title'];

                    // Calculate item total
                    $item_total = $product_price * $qty;
                    $total += $item_total;

                    echo "<tr>
                            <td>$product_name</td>
                            <td>$qty</td>
                            <td>₱" . number_format($product_price, 2) . "</td>
                            <td>₱" . number_format($item_total, 2) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="total">Grand Total</th>
                    <th class="total-value">₱<?php echo number_format($total, 2); ?></th>
                </tr>
            </tfoot>
        </table>

        <form action="process_order.php" method="post">
            <h2>Billing Details</h2>
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" required>
            
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            
            <label for="address">Address</label>
            <input type="text" name="address" id="address" required>
            
            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method" required>
                <option value="Cash on Delivery">Cash on Delivery</option>
                <!--<option value="paypal">Gcash</option>-->
            </select>
            
            <button type="submit">Place Order</button>
        </form>

        <div class="button-container">
            <a href="index.php" class="home-button">Continue Shopping</a>
            <!--<a href="previous_page.php" class="go-back-button">Go Back</a>-->
        </div>
    </div>
</body>
</html>
