<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if it hasn't started yet
}

// Get the current page name to prevent showing links to the same page
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header>
    <h1>E-Commerce Store</h1>
    <nav>
        <a href="/ecommerce/index.php">Home</a> |

        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="/ecommerce/admin/admin_dashboard.php">Admin Dashboard</a> |
            <?php endif; ?>
            <a href="/ecommerce/dashboard.php">User Dashboard</a> |
            <a href="/ecommerce/logout.php">Logout</a>
            <a href="/ecommerce/index.php">Home</a> |
            <a href="/ecommerce/cart.php">Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</a> |

        <?php else: ?>
            <?php if ($current_page !== 'login.php'): ?>
                <a href="/ecommerce/login.php">Login</a> |
            <?php endif; ?>
            <?php if ($current_page !== 'register.php'): ?>
                <a href="/ecommerce/register.php">Register</a>
            <?php endif; ?>
        <?php endif; ?>
    </nav>
</header>
