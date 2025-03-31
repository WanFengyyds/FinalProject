<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: ../home/login.php");
    exit;
}

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'fearofgod');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch all products
$sql = "SELECT users.username, orders.* FROM orders join users ON orders.user_id = users.user_id;";
$result = $mysqli->query($sql);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Fear of God</title>
    <link rel="icon" type="image/jpg" href="../assets/itoshiSae.jpg">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="../fonts/remixicon.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/jpg" href="../assets/itoshiSae.jpg">
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <a href="../home/home.php">FEAR OF GOD</a>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="orders.php">Orders</a></li>
        </ul>
        <div class="login-icon">
            <a href="../home/logout.php">Logout</a>
        </div>
    </nav>

    <!-- Products Content -->
    <section class="order-container">
        <div class="products-header">
            <h1 class="products-title">Order Management</h1>
        </div>

        <?php if (!empty($orders)): ?>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Total amount</th>
                        <th>Shipping Addresses</th>
                        <th>Billing Addresses</th>
                        <th>Payment Method</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo $order['username']; ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                            <td><?php echo $order['status']; ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo $order['shipping_address']; ?></td>
                            <td><?php echo $order['billing_address']; ?></td>
                            <td><?php echo $order['payment_method']; ?></td>

                            <td>
                                <button class="action-btn icon-btn" onclick="window.location.href='edit_order.php?id=<?php echo $order['order_id']; ?>'">
                                    <i class="ri-edit-box-line"></i>
                                </button>
                                <button class="action-btn icon-btn" onclick="confirmDelete(<?php echo $order['order_id']; ?>)">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </section>
    <div class="copyright">
        <p>© 2025 FEAR OF GOD ADMIN DASHBOARD. All Rights Reserved.</p>
    </div>
    </footer>

    <script>
        function confirmDelete(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                window.location.href = 'delete_product.php?id=' + productId;
            }
        }
    </script>
</body>

</html>