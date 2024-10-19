<?php
session_start();
include '../db/db.php';

// Ensure the user is an admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit();
}

// Get the product ID from the URL
$product_id = $_GET['id'];

// Fetch the current product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle form submission to update the product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    $update_stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, image_url = ? WHERE id = ?");
    $update_stmt->bind_param("sdsi", $name, $price, $image_url, $product_id);

    if ($update_stmt->execute()) {
        echo "<p>Product updated successfully! <a href='manage_products.php'>Back to Manage Products</a></p>";
    } else {
        echo "<p>Error: " . $update_stmt->error . "</p>";
    }

    $update_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <h2>Edit Product</h2>
    <form method="POST" action="edit_product.php?id=<?= $product_id ?>">
        <input type="text" name="name" value="<?= $product['name'] ?>" required><br>
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required><br>
        <input type="text" name="image_url" value="<?= $product['image_url'] ?>" required><br>
        <button type="submit">Update Product</button>
    </form>

    <?php include '../partials/footer.php'; ?>
</body>
</html>
