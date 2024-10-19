<?php
session_start();
include '../db/db.php';

// Ensure the user is an admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit();
}

// Get product details to pre-fill the form
$product_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Handle form submission to update the product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    $update_stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, image_url = ? WHERE id = ?");
    $update_stmt->bind_param("sdsi", $name, $price, $image_url, $product_id);

    if ($update_stmt->execute()) {
        // Redirect to Manage Products page after successful update
        header('Location: manage_products.php');
        exit();
    } else {
        echo "<p class='error'>Error: " . $update_stmt->error . "</p>";
    }

    $update_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script>
        function updatePreview() {
            const name = document.querySelector('input[name="name"]').value;
            const price = document.querySelector('input[name="price"]').value;
            const imageUrl = document.querySelector('input[name="image_url"]').value;

            document.getElementById('preview-name').textContent = name || "Product Name";
            document.getElementById('preview-price').textContent = price ? `$${parseFloat(price).toFixed(2)}` : "$0.00";
            document.getElementById('preview-image').src = imageUrl || "https://via.placeholder.com/250";
        }
    </script>
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <div class="edit-product-container">
        <h2>Edit Product</h2>
        <form method="POST" action="edit_product.php?id=<?= $product_id ?>" class="edit-product-form" oninput="updatePreview()">
            <input type="text" name="name" value="<?= $product['name'] ?>" placeholder="Product Name" required>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" placeholder="Price" required>
            <input type="text" name="image_url" value="<?= $product['image_url'] ?>" placeholder="Image URL" required>
            <button type="submit">Update Product</button>
        </form>

        <div class="product-preview">
            <h3>Product Preview</h3>
            <img id="preview-image" src="<?= $product['image_url'] ?>" alt="Product Image" class="preview-image">
            <p id="preview-name"><?= $product['name'] ?></p>
            <p id="preview-price">$<?= number_format($product['price'], 2) ?></p>
        </div>
    </div>

    <?php include '../partials/footer.php'; ?>
</body>
</html>
