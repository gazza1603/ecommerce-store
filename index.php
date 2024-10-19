<?php
session_start();
include 'db/db.php';

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to the cart
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    header('Location: index.php'); // Reload the page after adding to cart
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Store</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <h2>Our Products</h2>
    <div id="product-list">
        <?php
        $result = $conn->query("SELECT * FROM products");
        while ($product = $result->fetch_assoc()) {
            $image_url = $product['image_url'] ?: 'https://via.placeholder.com/250'; // Dummy image if none exists
            echo "
            <div class='product'>
                <img src='{$image_url}' alt='{$product['name']}' class='product-image'>
                <h3>{$product['name']}</h3>
                <p>Price: \$" . number_format($product['price'], 2) . "</p>
                <form method='POST' action='index.php'>
                    <input type='hidden' name='product_id' value='{$product['id']}'>
                    <button type='submit' class='add-to-cart'>Add to Cart</button>
                </form>
            </div>";
        }
        ?>
    </div>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
