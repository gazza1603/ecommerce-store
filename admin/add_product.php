<?php
session_start();
include '../db/db.php';

// Ensure the user is an admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit();
}

// Handle form submission to add a new product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("INSERT INTO products (name, price, image_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $price, $image_url);

    if ($stmt->execute()) {
        echo "<p class='success'>Product added successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <div class="add-product-container">
        <h2>Add New Product</h2>
        <form method="POST" action="add_product.php" class="add-product-form">
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="text" name="image_url" placeholder="Image URL" required>
            <button type="submit">Add Product</button>
        </form>
    </div>

    <?php include '../partials/footer.php'; ?>
</body>
</html>
