<?php
session_start();
include 'db/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch cart items
$cart_items = [];
$total_amount = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $product['quantity'] = $quantity;
        $cart_items[] = $product;
        $total_amount += $product['price'] * $quantity;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];

    // Insert new order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date) VALUES (?, NOW())");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert order items
    foreach ($cart_items as $item) {
        $stmt = $conn->prepare(
            "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("iii", $order_id, $item['id'], $item['quantity']);
        $stmt->execute();
    }

    // Clear cart and redirect to confirmation
    unset($_SESSION['cart']);
    header('Location: order_confirmation.php?order_id=' . $order_id);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div class="checkout-container">
        <h2>Checkout</h2>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="total-label">Total Amount</td>
                        <td class="total-value">$<?= number_format($total_amount, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <form method="POST" action="checkout.php" class="checkout-form">
            <h3>Shipping Details</h3>
            <textarea name="shipping_address" placeholder="Enter your shipping address" required></textarea>
            <button type="submit">Place Order</button>
        </form>
    </div>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
