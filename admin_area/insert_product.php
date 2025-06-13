<!DOCTYPE html>
<html>
<head>
    <title>Inserting Product</title>
    <style>
        body {font-family: Arial, sans-serif;background-color: #f4f4f4;margin: 0;padding: 0;display: flex;justify-content: center;align-items: center;height: 100vh;}
        .form-container {background: #fff; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-radius: 5px; width: 400px; text-align: center;}
        h2 { margin-bottom: 20px; color: #333;}
        input[type="text"], input[type="file"], select, textarea {
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
            border-radius: 5px;
            width: 100%;
			font-weight:bold;
        }
        button:hover {
            background: #FF0000;
        }
        .home-button {
            background: #00008B;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            width: 100%;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
			
        }
        .home-button:hover {
            background: #FF0000;
        }
        .footer {
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: white;
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Insert New Product</h2>
    <form action="insert_product.php" method="post" enctype="multipart/form-data">
        <input type="text" name="product_title" placeholder="Product Title" required>
        <select name="product_cat" required>
            <option value="">Select a Category</option>
            <?php
            include("includes/db.php");
            $get_cats = "SELECT * FROM categories";
            $run_cats = mysqli_query($con, $get_cats);
            while ($row_cats = mysqli_fetch_array($run_cats)) {
                $cat_id = $row_cats['cat_id'];
                $cat_title = $row_cats['cat_title'];
                echo "<option value='$cat_id'>$cat_title</option>";
            }
            ?>
        </select>
        <select name="product_brands" required>
            <option value="">Select a Brand</option>
            <?php
            $get_brands = "SELECT * FROM brands";
            $run_brands = mysqli_query($con, $get_brands);
            while ($row_brands = mysqli_fetch_array($run_brands)) {
                $brand_id = $row_brands['brand_id'];
                $brand_title = $row_brands['brand_title'];
                echo "<option value='$brand_id'>$brand_title</option>";             
            }
            ?>
        </select>
        <input type="file" name="product_image" required>
        <input type="text" name="product_price" placeholder="Product Price" required>
        <textarea name="product_desc" placeholder="Product Description" cols="20" rows="10" required></textarea>
        <input type="text" name="product_keywords" placeholder="Product Keywords" size="50" required>
        <button type="submit" name="insert_post">Insert Now</button>
    </form>
	<form action="/ecommerce/index.php" method="get"> 
	<button type="submit" class="home-button">Go Back</button> </form>
</div>
<div class="footer">
    &copy; 2024 by Ronelio Estoya
</div>
</body>
</html>
<?php
if (isset($_POST['insert_post'])) {
    include("includes/db.php");
    // Getting the text data from the fields
    $product_title = $_POST['product_title'];
    $product_cat = $_POST['product_cat'];
    $product_brand = $_POST['product_brands'];
    $product_price = $_POST['product_price'];
    $product_desc = $_POST['product_desc'];
    $product_keywords = $_POST['product_keywords'];

    // Getting the image from the field
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp = $_FILES['product_image']['tmp_name'];

    // Uploading image (error handling can be added)
    $upload_result = move_uploaded_file($product_image_tmp, "product_images/$product_image");

    // Constructing SQL query (consider prepared statements)
    $insert_product = "INSERT INTO product (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $insert_product);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssss", $product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Product has been inserted!')</script>";
            echo "<script>window.open('insert_product.php', '_self')</script>";
        } else {
            echo "Error inserting product: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
    }
}
?>
