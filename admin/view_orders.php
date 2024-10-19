<?php
// Start session and include database connection
session_start();
require_once '../db/db.php'; // Correct path to your database file

// Check if user is an admin, if not redirect to home
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: ../index.php");
    exit();
}

// Fetch all orders with their order items
$sql = "SELECT orders.id, orders.user_id, orders.order_date, 
        users.email, 
        GROUP_CONCAT(CONCAT(products.name, ' (x', order_items.quantity, ')') SEPARATOR ', ') AS items, 
        SUM(products.price * order_items.quantity) AS total_amount
        FROM orders
        JOIN order_items ON orders.id = order_items.order_id
        JOIN products ON order_items.product_id = products.id
        JOIN users ON orders.user_id = users.id
        GROUP BY orders.id
        ORDER BY orders.order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders - Shadow Wood Admin</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Shadow Wood Store</h1>
        <nav>
            <a href="../index.php">Home</a> |
            <a href="admin_dashboard.php">Admin Dashboard</a> |
            <a href="../logout.php">Logout</a>
        </nav>
    </header>

    <div class="orders-container">
        <h2>All Orders</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td><?php echo htmlspecialchars($row['items']); ?></td>
                            <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>

    <?php include('../partials/footer.php'); ?>
</body>
</html>
