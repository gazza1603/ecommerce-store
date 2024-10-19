<?php
session_start();
include '../db/db.php';

// Ensure the user is an admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit();
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $product_id");
    header('Location: manage_products.php');
    exit();
}

// Fetch all products
$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <h2>Manage Products</h2>
    <a href="add_product.php"><button>Add New Product</button></a>

    <ul>
        <?php while ($product = $result->fetch_assoc()) { ?>
            <li>
                <strong><?= $product['name'] ?></strong> - $<?= $product['price'] ?>
                <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a> |
                <a href="manage_products.php?delete=<?= $product['id'] ?>" 
                   onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
            </li>
        <?php } ?>
    </ul>

    <?php include '../partials/footer.php'; ?>
</body>
</html>
