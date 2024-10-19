<?php
session_start();
include 'db/db.php';

$total_amount = 0;

// Fetch cart items for the summary
$cart_items = [];
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
        
        <h3>Order Summary</h3>
        <table class="checkout-table">
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
                        <td><?= htmlspecialchars($item['name']) ?></td>
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

        <form action="place_order.php" method="POST" class="checkout-form">
            <div class="form-group">
                <label for="shipping_address">Shipping Details</label>
                <textarea id="shipping_address" name="shipping_address" rows="3" placeholder="Enter your shipping address" required></textarea>
            </div>

            <h3>Card Details</h3>
            <div class="card-details">
                <input type="text" name="card_name" placeholder="Name on Card" required>
                <input type="text" name="card_number" placeholder="Card Number" maxlength="16" required>
                <input type="text" name="expiry" placeholder="MM/YY" maxlength="5" required>
                <input type="text" name="cvv" placeholder="CVV" maxlength="3" required>
            </div>

            <button type="submit" class="place-order-button">Place Order</button>
        </form>
    </div>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
