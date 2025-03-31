<?php
// Start session and check admin privileges
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: ../home/login.php");
    exit;
}

// Database connection (adjust according to your setup)
$mysqli = new mysqli('localhost', 'root', '', 'fearofgod');


if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
// Fetch all users from database
$sql = "SELECT user_id, username, email, role, created_at FROM users ";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Fear of God</title>
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
    <section class="products-container">
        <div class="products-header">
            <h1 class="products-title">User Management</h1>
            <a href="add_user.php" class="add-product-btn">Add New User</a>
        </div>

        <?php if (!empty($users)): ?>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['role']; ?></td>
                            <td><?php echo $user['created_at']; ?></td>
                            <td>
                                <button class="action-btn icon-btn" onclick="window.location.href='edit_user.php?id=<?php echo $user['user_id']; ?>'">
                                    <i class="ri-edit-box-line"></i>
                                </button>
                                <button class="action-btn icon-btn" onclick="confirmDelete(<?php echo $user['user_id']; ?>)">
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
                window.location.href = 'delete_user.php?id=' + productId;
            }
        }
    </script>
</body>

</html>