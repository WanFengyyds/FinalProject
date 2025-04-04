<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../home/login.php");
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'fearofgod');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get user details
$user_id = $_SESSION['user_id'];
$user_result = $mysqli->query("SELECT * FROM users WHERE user_id = $user_id");
if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

// Get user orders
$sql_orders = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC";
$orders_result = $mysqli->query($sql_orders);
$orders = [];
if ($orders_result->num_rows > 0) {
    while ($row = $orders_result->fetch_assoc()) {
        $orders[] = $row;
    }
}

// Get shipping addresses
$sql_addresses = "SELECT * FROM shipping_addresses WHERE user_id = $user_id";
$addresses_result = $mysqli->query($sql_addresses);
$addresses = [];
if ($addresses_result->num_rows > 0) {
    while ($row = $addresses_result->fetch_assoc()) {
        $addresses[] = $row;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Fear of God</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="account.css">
    <link rel="icon" type="image/jpg" href="../assets/itoshiSae.jpg">
</head>

<body>
    <nav>
        <div class="logo">
            <a href="../home/home.php">FEAR OF GOD</a>
        </div>
        <ul class="nav-links">
            <li><a href="../home/home.php">Home</a></li>
            <li><a href="../shop/shop.php">Shop</a></li>
            <li><a href="user_account.php">My Account</a></li>
            <li><a href="../cart/cart.php">Cart</a></li>
        </ul>
        <div class="login-icon">
            <a href="../home/logout.php">Logout</a>
        </div>
    </nav>

    <section class="user-account">
        <div class="account-header">
            <h1>My Account</h1>
            <p>Welcome back, <?php echo htmlspecialchars($user['username']); ?></p>
        </div>

        <div class="account-sections">
            <!-- Personal Information Section -->
            <div class="account-section">
                <h2>Personal Information</h2>
                <div class="account-details">
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Member since:</strong> <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                </div>
                <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
            </div>

            <!-- Shipping Addresses Section -->
            <div class="account-section">
                <h2>Shipping Addresses</h2>
                <?php if (!empty($addresses)): ?>
                    <div class="address-grid">
                        <?php foreach ($addresses as $address): ?>
                            <div class="address-card <?php echo $address['is_default'] ? 'default-address' : ''; ?>">
                                <h3><?php echo htmlspecialchars($address['recipient_name']); ?></h3>
                                <p><?php echo htmlspecialchars($address['address']); ?></p>
                                <p><?php echo htmlspecialchars($address['city']); ?>, <?php echo htmlspecialchars($address['state']); ?> <?php echo htmlspecialchars($address['zip_code']); ?></p>
                                <p><?php echo htmlspecialchars($address['country']); ?></p>
                                <p>Phone: <?php echo htmlspecialchars($address['phone_number']); ?></p>
                                <?php if ($address['is_default']): ?>
                                    <span class="default-badge">Default</span>
                                <?php endif; ?>
                                <div class="address-actions">
                                    <a href="edit_address.php?id=<?php echo $address['address_id']; ?>" class="edit-btn">Edit</a>
                                    <a href="delete_address.php?id=<?php echo $address['address_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this address?')">Delete</a>
                                    <?php if (!$address['is_default']): ?>
                                        <a href="set_default_address.php?id=<?php echo $address['address_id']; ?>" class="default-btn">Set as Default</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No shipping addresses saved.</p>
                <?php endif; ?>
                <a href="add_address.php" class="add-btn">Add New Address</a>
            </div>

            <!-- Order History Section -->
            <div class="account-section">
                <h2>Order History</h2>
                <?php if (!empty($orders)): ?>
                    <div class="orders-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['order_id']; ?></td>
                                        <td><?php echo date('F j, Y', strtotime($order['order_date'])); ?></td>
                                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                                        <td><a href="order_details.php?id=<?php echo $order['order_id']; ?>" class="view-btn">View Details</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>You haven't placed any orders yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="copyright">
        <p>© 2025 FEAR OF GOD. All Rights Reserved.</p>
    </div>
</body>

</html>s