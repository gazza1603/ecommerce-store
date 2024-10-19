<?php
session_start();
include 'db/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user orders
$stmt = $conn->prepare(
    "SELECT orders.id, orders.order_date, products.name, order_items.quantity 
     FROM orders 
     JOIN order_items ON orders.id = order_items.order_id 
     JOIN products ON order_items.product_id = products.id 
     WHERE orders.user_id = ? 
     ORDER BY orders.order_date DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Your Orders</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div class="orders-container">
        <h2>Your Orders</h2>

        <?php if ($orders->num_rows > 0): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Order Date</th>
                        <th>Products</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= $order['order_date'] ?></td>
                            <td><?= $order['name'] ?></td>
                            <td><?= $order['quantity'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no orders yet.</p>
        <?php endif; ?>
    </div>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
