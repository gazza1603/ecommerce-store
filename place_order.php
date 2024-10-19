<?php
session_start();
include 'db/db.php'; // If you need DB connection for customer info

// Clear the cart after order is placed
unset($_SESSION['cart']);

// Get the customer email (assuming it's stored in the session or retrieved from the DB)
$customer_email = $_SESSION['user_email'] ?? 'customer@example.com'; // Replace with actual session value

// Email content
$subject = "Order Confirmation - E-Commerce Store";
$message = "
    <h2>Thank you for your order!</h2>
    <p>Your order has been placed successfully. You can view your order history in your dashboard.</p>
    <p>If you have any questions, contact us at support@ecommerce-store.com</p>
";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: E-Commerce Store <no-reply@ecommerce-store.com>' . "\r\n";

// Send email
if (mail($customer_email, $subject, $message, $headers)) {
    $email_status = "A confirmation email has been sent to your inbox.";
} else {
    $email_status = "We couldn't send the confirmation email at this time.";
}
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
        <div class="confirmation-card">
            <h2>🎉 Order Placed Successfully!</h2>
            <p>Thank you for your purchase. Your order has been successfully placed.</p>
            <p><?= $email_status ?></p>
            <a href="dashboard.php" class="order-history-link">View Your Order History</a>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
