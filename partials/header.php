<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if it hasn't started yet
}

// Get the current page name to avoid showing links to the same page
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header>
    <nav class="navbar">
        <div class="dropdown">
            <button class="dropbtn">Menu</button>
            <div class="dropdown-content">
                <a href="/ecommerce/index.php">Home</a>
                <a href="/ecommerce/about_us.php">About Us</a>
                <a href="/ecommerce/contact_us.php">Contact Us</a>
                <a href="/ecommerce/index.php">Shop</a>
            </div>
        </div>

        <div class="site-title">
            <h1>Shadow Wood Designs</h1>
        </div>

        <div class="user-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <a href="/ecommerce/admin/admin_dashboard.php">Admin Dashboard</a> |
                <?php endif; ?>
                <a href="/ecommerce/dashboard.php">User Dashboard</a> |
                <a href="/ecommerce/cart.php">Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</a> |
                <a href="/ecommerce/checkout.php">Checkout</a> |
                <a href="/ecommerce/logout.php">Logout</a>
            <?php else: ?>
                <?php if ($current_page !== 'login.php'): ?>
                    <a href="/ecommerce/login.php">Login</a> |
                <?php endif; ?>
                <?php if ($current_page !== 'register.php'): ?>
                    <a href="/ecommerce/register.php">Register</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </nav>
</header>
