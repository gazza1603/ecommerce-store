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
        <a href="index.php">Home</a> |

        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="admin/admin_dashboard.php">Admin Dashboard</a> |
            <?php endif; ?>
            <a href="dashboard.php">User Dashboard</a> |
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <?php if ($current_page !== 'login.php'): ?>
                <a href="login.php">Login</a> |
            <?php endif; ?>
            <?php if ($current_page !== 'register.php'): ?>
                <a href="register.php">Register</a>
            <?php endif; ?>
        <?php endif; ?>
    </nav>
</header>
