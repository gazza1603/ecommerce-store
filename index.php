<?php
session_start();
include 'db/db.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart request
if (isset($_GET['add_to_cart'])) {
    $product_id = $_GET['add_to_cart'];
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1; // Add product with quantity 1
    } else {
        $_SESSION['cart'][$product_id]++; // Increase quantity if already in cart
    }
    header('Location: index.php'); // Redirect to prevent resubmission
    exit();
}

// Fetch all products from the database
$result = $conn->query("SELECT * FROM products");
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
        <?php while ($product = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="<?= $product['image_url'] ?>" alt="<?= $product['name'] ?>" class="product-image">
                <h3><?= $product['name'] ?></h3>
                <p>Price: $<?= number_format($product['price'], 2) ?></p>
                <form method="GET" action="index.php">
                    <input type="hidden" name="add_to_cart" value="<?= $product['id'] ?>">
                    <button type="submit" class="add-to-cart">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
