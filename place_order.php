<?php
session_start();
include 'db/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$conn->query("INSERT INTO orders (user_id) VALUES ($user_id)");
$order_id = $conn->insert_id;

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $conn->query("INSERT INTO order_items (order_id, product_id, quantity) VALUES ($order_id, $product_id, $quantity)");
}

unset($_SESSION['cart']); // Clear cart

echo "<h2>Order placed successfully!</h2>";
echo "<p><a href='dashboard.php'>View your order history</a></p>";
?>
