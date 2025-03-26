<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}else {
    $mysqli = new mysqli('localhost', 'root', '', 'fearofgod');

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    // Direct INSERT without hashing
    $sql = "SELECT COUNT(*) AS user_count FROM users;";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_count = $row['user_count'];
    } else {
        $user_count = 0;
    }

    $sql = "SELECT COUNT(*) AS order_count FROM orders;";

    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_orders = $row['order_count'];
    } else {
        $total_orders = 0;
    }

    $sql = 'SELECT SUM(total_amount) AS orders_sum FROM orders;';
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $orders_sum = $row['orders_sum'];
    } else {
        $orders_sum = 0;
    }


    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Fear of God</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <a href="home.php">FEAR OF GOD</a>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="orders.php">Orders</a></li>
        </ul>
        <div class="login-icon">
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <?php

    ?>
    <!-- Dashboard Content -->
    <section class="dashboard">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Admin Dashboard</h1>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <?php
                    echo "<div class='stat-value'>$user_count</div>";
                    echo "<div class='stat-label'>TOTAL USERS</div>";
                ?>
                
            </div>
            
            <div class="stat-card">
                <?php
                    echo "<div class='stat-value'>$total_orders</div>";
                    echo "<div class='stat-label'>TOTAL ORDERS</div>";
                ?>
            </div>
            
            <div class="stat-card">
                <?php
                    echo "<div class='stat-value'>$orders_sum$</div>";
                    echo "<div class='stat-label'>TOTAL SALES</div>";
                ?>

            </div>
            
            <div class="stat-card">
                <div class="stat-value">46.43%</div>
                <div class="stat-label">GROWTH RATE</div>
            </div>
        </div>
        
        <!-- Main Content Grid -->
        
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-grid">
            <div class="footer-column">
                <h4>Admin</h4>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="orders.php">Orders</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Reports</h4>
                <ul>
                    <li><a href="#">Sales</a></li>
                    <li><a href="#">Traffic</a></li>
                    <li><a href="#">Products</a></li>
                    <li><a href="#">Customers</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Settings</h4>
                <ul>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Security</a></li>
                    <li><a href="#">Notifications</a></li>
                    <li><a href="#">Preferences</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Feedback</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>© 2025 FEAR OF GOD ADMIN DASHBOARD. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>