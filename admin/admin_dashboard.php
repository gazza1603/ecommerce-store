<?php
session_start();
include '../db/db.php';

// Ensure the user is an admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>

    <div class="admin-dashboard">
        <h2>Welcome, Admin!</h2>
        <div class="admin-cards">
            <a href="add_product.php" class="admin-card">
                <h3>Add New Product</h3>
                <p>Create a new product listing.</p>
            </a>
            <a href="manage_products.php" class="admin-card">
                <h3>Manage Products</h3>
                <p>Edit or delete existing products.</p>
            </a>
            <a href="view_orders.php" class="admin-card">
                <h3>View All Orders</h3>
                <p>See all customer orders.</p>
            </a>
        </div>
    </div>

    <?php include '../partials/footer.php'; ?>
</body>
</html>
