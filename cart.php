<?php
session_start();
include 'db/db.php';

// Fetch product details for items in the cart
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

// Handle product removal
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header('Location: cart.php'); // Redirect to refresh the cart
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div class="cart-container">
        <h2>Your Cart</h2>
        <?php if (!empty($cart_items)): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <?php $total = $item['price'] * $item['quantity']; ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($total, 2) ?></td>
                            <td>
                                <a href="cart.php?remove=<?= $item['id'] ?>" class="remove-link">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="grand-total-label">Grand Total</td>
                        <td class="grand-total-value">$<?= number_format($total_amount, 2) ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div class="checkout-button-container">
                <a href="checkout.php" class="checkout-button">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
