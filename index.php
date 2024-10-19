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
            $image_url = $product['image_url'] ?: 'https://via.placeholder.com/250'; // Fallback image if none exists
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

    <h2>Featured Products</h2>
    <div class="carousel">
        <?php
        // Fetch product images for the carousel
        $carouselResult = $conn->query("SELECT image_url, name FROM products LIMIT 5");
        if ($carouselResult->num_rows > 0) {
            $first = true;
            while ($row = $carouselResult->fetch_assoc()) {
                $image_url = $row['image_url'] ?: 'https://via.placeholder.com/400x200';
                $display = $first ? 'block' : 'none'; // Show the first image by default
                $first = false;
                echo "<img src='{$image_url}' alt='{$row['name']}' class='carousel-image' style='display: {$display};'>";
            }
        } else {
            echo "<p>No products available for the carousel.</p>";
        }
        ?>
    </div>

    <script>
        let currentImage = 0;
        const images = document.querySelectorAll('.carousel-image');

        function showNextImage() {
            images[currentImage].style.display = 'none'; // Hide current image
            currentImage = (currentImage + 1) % images.length; // Move to the next image (loop back to the first if needed)
            images[currentImage].style.display = 'block'; // Show the next image
        }
        setInterval(showNextImage, 3000); // Change image every 3 seconds
    </script>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
