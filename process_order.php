<?php
session_start(); // Start the session at the very beginning

// Include database connection and functions
include("admin_area/includes/db.php"); // Ensure this path is correct
include("functions/functions.php"); // Ensure this path is correct

// Redirect if the form was not submitted
if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['address']) || !isset($_POST['payment_method'])) {
    header("Location: checkout.php"); // Redirect back to checkout if accessed directly
    exit();
}

$ip = getIp(); // Get user's IP address

// Retrieve customer details from the form
$customer_name = mysqli_real_escape_string($con, $_POST['name']);
$customer_email = mysqli_real_escape_string($con, $_POST['email']);
$customer_address = mysqli_real_escape_string($con, $_POST['address']);
$payment_method = mysqli_real_escape_string($con, $_POST['payment_method']);

$order_total = 0; // Initialize total for the order

// Start a database transaction for atomicity
mysqli_autocommit($con, FALSE); // Disable autocommit
$all_queries_successful = true;

// 1. Get cart items to calculate total and insert into pending_orders
$get_cart_items_query = "SELECT product_id, qty FROM cart WHERE ip_address='$ip'";
$run_cart_items = mysqli_query($con, $get_cart_items_query);

if (!$run_cart_items) {
    error_log("Error fetching cart items for order: " . mysqli_error($con));
    $all_queries_successful = false;
}

$cart_products = []; // Store products for display on confirmation page
if ($all_queries_successful) {
    while ($cart_item = mysqli_fetch_assoc($run_cart_items)) {
        $product_id = $cart_item['product_id'];
        $qty = $cart_item['qty'];

        // Fetch product details for current price
        $get_product_query = "SELECT Product_title, Product_price FROM product WHERE Product_id='$product_id'";
        $run_product = mysqli_query($con, $get_product_query);

        if (!$run_product || mysqli_num_rows($run_product) == 0) {
            error_log("Error fetching product details for ID: $product_id - " . mysqli_error($con));
            $all_queries_successful = false;
            break; // Exit loop if product not found or query fails
        }
        $product = mysqli_fetch_assoc($run_product);

        $product_title = $product['Product_title'];
        $product_price_at_purchase = $product['Product_price']; // Record price at time of purchase

        $item_total = $product_price_at_purchase * $qty;
        $order_total += $item_total;

        $cart_products[] = [
            'id' => $product_id,
            'title' => $product_title,
            'qty' => $qty,
            'price' => $product_price_at_purchase,
            'item_total' => $item_total
        ];
    }
}

// 2. Insert into customer_orders table
$order_status = 'Pending'; // Default status
$insert_order_query = "INSERT INTO customer_orders (customer_name, customer_email, customer_address, payment_method, order_total, order_date, order_status, ip_address) 
                       VALUES ('$customer_name', '$customer_email', '$customer_address', '$payment_method', '$order_total', NOW(), '$order_status', '$ip')";

if ($all_queries_successful) {
    $run_insert_order = mysqli_query($con, $insert_order_query);
    if (!$run_insert_order) {
        error_log("Error inserting into customer_orders: " . mysqli_error($con));
        $all_queries_successful = false;
    } else {
        $order_id = mysqli_insert_id($con); // Get the ID of the newly inserted order
    }
}

// 3. Insert into pending_orders (or order_items) table
if ($all_queries_successful && !empty($cart_products)) {
    foreach ($cart_products as $item) {
        $pro_id = $item['id'];
        $pro_qty = $item['qty'];
        $pro_price_at_purchase = $item['price'];

        $insert_pending_query = "INSERT INTO pending_orders (order_id, product_id, qty, price_at_purchase, ip_address, order_status) 
                                 VALUES ('$order_id', '$pro_id', '$pro_qty', '$pro_price_at_purchase', '$ip', '$order_status')"; // Added ip_address and order_status for consistency

        $run_insert_pending = mysqli_query($con, $insert_pending_query);
        if (!$run_insert_pending) {
            error_log("Error inserting into pending_orders for product ID $pro_id: " . mysqli_error($con));
            $all_queries_successful = false;
            break; // Exit loop if any insertion fails
        }
    }
} elseif ($all_queries_successful && empty($cart_products)) {
    // This could happen if somehow the cart was empty after fetching, but before order insertion
    // It's a good idea to handle this case, maybe redirect back to cart with a message.
    error_log("Attempted to place an order with an empty cart for IP: $ip");
    $all_queries_successful = false; // Treat as failure if cart is empty
}


// 4. Clear the cart if all operations were successful
if ($all_queries_successful) {
    $clear_cart_query = "DELETE FROM cart WHERE ip_address='$ip'";
    $run_clear_cart = mysqli_query($con, $clear_cart_query);
    if (!$run_clear_cart) {
        error_log("Error clearing cart: " . mysqli_error($con));
        $all_queries_successful = false;
    }
}

// Commit or Rollback transaction
if ($all_queries_successful) {
    mysqli_commit($con);
    $order_placed_successfully = true;
} else {
    mysqli_rollback($con);
    $order_placed_successfully = false;
}

mysqli_autocommit($con, TRUE); // Re-enable autocommit

?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            color: #28a745; /* Green for success */
            margin-bottom: 20px;
        }
        .error-message {
            color: #dc3545; /* Red for error */
            margin-bottom: 20px;
        }
        p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #555;
            margin-bottom: 10px;
        }
        .order-details {
            text-align: left;
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 20px;
        }
        .order-details h2 {
            color: #333;
            margin-bottom: 15px;
        }
        .order-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .order-details th, .order-details td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        .order-details th {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .order-details .total-row {
            font-weight: bold;
            font-size: 1.2em;
            color: #d9534f;
        }
        .button-link {
            display: inline-block;
            background: #007bff; /* Blue */
            color: #fff;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.1em;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .button-link:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($order_placed_successfully): ?>
            <h1>Order Placed Successfully!</h1>
            <p>Thank you for your purchase. Your order has been placed and will be processed shortly.</p>
            <p>Your Order ID is: <strong><?php echo $order_id; ?></strong></p>

            <div class="order-details">
                <h2>Order Summary</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Item Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_products as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['title']); ?></td>
                                <td><?php echo htmlspecialchars($item['qty']); ?></td>
                                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                                <td>₱<?php echo number_format($item['item_total'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="3" style="text-align:right;">Grand Total:</td>
                            <td>₱<?php echo number_format($order_total, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>

                <h2>Billing Information</h2>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($customer_name); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($customer_email); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($customer_address); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment_method); ?></p>
            </div>

            <a href="index.php" class="button-link">Continue Shopping</a>

        <?php else: ?>
            <h1 class="error-message">Order Placement Failed!</h1>
            <p>We apologize, but there was an error processing your order. Please try again.</p>
            <p>If the problem persists, please contact support.</p>
            <a href="checkout.php" class="button-link" style="background-color: #6c757d;">Go Back to Checkout</a>
            <a href="index.php" class="button-link">Continue Shopping</a>
        <?php endif; ?>
    </div>
</body>
</html>