<?php
session_start();
include 'db/db.php';

// Handle quantity updates
if (isset($_POST['update'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    header('Location: cart.php');
    exit();
}

// Remove item from cart
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header('Location: cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    <h2>Your Cart</h2>
    <ul>
        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
            $product = $result->fetch_assoc();
            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;

            echo "
            <li>
                {$product['name']} - \${$product['price']} x 
                <form method='POST' style='display:inline-block;' action='cart.php'>
                    <input type='hidden' name='product_id' value='$product_id'>
                    <input type='number' name='quantity' value='$quantity' min='0'>
                    <button type='submit' name='update'>Update</button>
                </form>
                <a href='cart.php?remove=$product_id'>Remove</a>
            </li>";
        }
        ?>
    </ul>
    <h3>Total: $<?= $total ?></h3>
    <a href="place_order.php"><button>Place Order</button></a>
</body>
</html>
