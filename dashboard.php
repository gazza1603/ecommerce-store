<?php
session_start();
include 'db/db.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'];

// Fetch orders for the current user or all orders if admin
$sql = $is_admin ? "SELECT * FROM orders" : "SELECT * FROM orders WHERE user_id = $user_id";
$orders = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <h2>Your Orders</h2>
    <ul>
        <?php
        while ($order = $orders->fetch_assoc()) {
            echo "<li>Order #{$order['id']} - {$order['order_date']}";
            echo "<ul>";

            $order_id = $order['id'];
            $items = $conn->query(
                "SELECT products.name, order_items.quantity 
                FROM order_items 
                JOIN products ON order_items.product_id = products.id 
                WHERE order_items.order_id = $order_id"
            );

            while ($item = $items->fetch_assoc()) {
                echo "<li>{$item['name']} (x{$item['quantity']})</li>";
            }

            echo "</ul></li>";
        }
        ?>
    </ul>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
