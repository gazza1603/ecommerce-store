<?php
session_start();
include 'db/db.php';

// Fetch product details from the cart session
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
    header('Location: cart.php');
    exit();
}

// Handle cart update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $_SESSION['cart'][$product_id] = max(1, (int)$quantity); // Ensure quantity is at least 1
    }
    header('Location: cart.php');
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
            <form method="POST" action="cart.php">
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
                            <tr>
                                <td><?= $item['name'] ?></td>
                                <td>$<?= number_format($item['price'], 2) ?></td>
                                <td>
                                    <input type="number" name="quantity[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="1">
                                </td>
                                <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                <td>
                                    <a href="cart.php?remove=<?= $item['id'] ?>" class="remove-link">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="total-label">Total:</td>
                            <td class="total-value">$<?= number_format($total_amount, 2) ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="cart-actions">
                    <button type="submit" name="update_cart" class="update-button">Update Cart</button>
                    <a href="checkout.php" class="place-order-button">Place Order</a>
                </div>

            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
