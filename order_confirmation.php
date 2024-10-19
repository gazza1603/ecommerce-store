<?php
session_start();
include 'db/db.php';

// Ensure order ID is provided in the URL
if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details
$stmt = $conn->prepare(
    "SELECT orders.id, orders.order_date, users.username 
     FROM orders 
     JOIN users ON orders.user_id = users.id 
     WHERE orders.id = ?"
);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Fetch order items
$stmt = $conn->prepare(
    "SELECT products.name, order_items.quantity, products.price 
     FROM order_items 
     JOIN products ON order_items.product_id = products.id 
     WHERE order_items.order_id = ?"
);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div class="confirmation-container">
        <h2>Order Confirmation</h2>
        <p>Thank you, <?= $order['username'] ?>, for your order!</p>
        <p>Order Number: <strong>#<?= $order['id'] ?></strong></p>
        <p>Order Date: <?= $order['order_date'] ?></p>

        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $grand_total = 0; ?>
                <?php while ($item = $order_items->fetch_assoc()): ?>
                    <?php $total = $item['quantity'] * $item['price']; ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>$<?= number_format($total, 2) ?></td>
                    </tr>
                    <?php $grand_total += $total; ?>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="grand-total-label">Grand Total</td>
                    <td class="grand-total-value">$<?= number_format($grand_total, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
