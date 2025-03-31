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

// Initialize variables
$order = [
    'order_id' => '',
    'user_id' => '',
    'order_date' => '',
    'total_amount' => '',
    'status' => '',
    'shipping_address' => '',
    'billing_address' => '',
    'payment_method' => ''
];
$error = '';
$order_items = [];

// Check if order ID is provided
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Fetch order data
    $sql = "SELECT * FROM orders WHERE order_id = '$order_id'";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();

        // Fetch order items
        $items_sql = "SELECT i.*, p.name as product_name 
                     FROM itemorder i 
                     LEFT JOIN product p ON i.product_id = p.product_id 
                     WHERE i.order_id = '$order_id'";
        $items_result = $mysqli->query($items_sql);

        if ($items_result->num_rows > 0) {
            while ($row = $items_result->fetch_assoc()) {
                $order_items[] = $row;
            }
        }
    } else {
        $error = "Order not found";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $shipping_address = $_POST['shipping_address'];
    $billing_address = $_POST['billing_address'];
    $payment_method = $_POST['payment_method'];

    // Basic validation
    if (empty($status)) {
        $error = "Status is required";
    } else {
        // Update order in database
        $sql = "UPDATE orders SET 
                status = '$status',
                shipping_address = '$shipping_address',
                billing_address = '$billing_address',
                payment_method = '$payment_method'
                WHERE order_id = '$order_id'";

        if ($mysqli->query($sql) === TRUE) {
            header("Location: orders.php?success=Order updated successfully");
            exit;
        } else {
            $error = "Error updating order: " . $mysqli->error;
        }
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order - Fear of God</title>
    <link rel="stylesheet" href="dashboard.css">
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

    <!-- Edit Order Content -->
    <section class="edit-product-container">
        <div class="edit-product-header">
            <h1 class="edit-product-title">Edit Order #<?php echo $order['order_id']; ?></h1>
            <a href="orders.php" class="back-btn">← Back to Orders</a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="edit-product-form" method="POST" action="edit_order.php">
            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">

            <div class="form-group">
                <label for="order_date">Order Date</label>
                <input type="text" id="order_date" class="form-control"
                    value="<?php echo date('Y-m-d H:i:s', strtotime($order['order_date'])); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="total_amount">Total Amount ($)</label>
                <input type="text" id="total_amount" class="form-control"
                    value="<?php echo number_format($order['total_amount'], 2); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="In elaborazione" <?php echo ($order['status'] == 'In elaborazione') ? 'selected' : ''; ?>>In elaborazione</option>
                    <option value="Spedito" <?php echo ($order['status'] == 'Spedito') ? 'selected' : ''; ?>>Spedito</option>
                    <option value="Consegnato" <?php echo ($order['status'] == 'Consegnato') ? 'selected' : ''; ?>>Consegnato</option>
                    <option value="Cancellato" <?php echo ($order['status'] == 'Cancellato') ? 'selected' : ''; ?>>Cancellato</option>
                </select>
            </div>

            <div class="form-group">
                <label for="shipping_address">Shipping Address</label>
                <textarea id="shipping_address" name="shipping_address" class="form-control form-textarea"><?php echo ($order['shipping_address']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="billing_address">Billing Address</label>
                <textarea id="billing_address" name="billing_address" class="form-control form-textarea"><?php echo ($order['billing_address']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <input type="text" id="payment_method" name="payment_method" class="form-control"
                    value="<?php echo ($order['payment_method']); ?>">
            </div>

            <div class="order-items">
                <h3>Order Items</h3>
                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td><?php echo $item['product_name'] ? $item['product_name'] : 'Product Deleted'; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>$<?php echo number_format($item['price_at_time_of_purchase'], 2); ?></td>
                                <td>$<?php echo number_format($item['quantity'] * $item['price_at_time_of_purchase'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="btn-group">
                <button type="submit" class="submit-btn">Update Order</button>
                <a href="orders.php" class="submit-btn">Cancel</a>
            </div>
        </form>
    </section>

    <footer>
        <div class="copyright">
            <p>© 2025 FEAR OF GOD ADMIN DASHBOARD. All Rights Reserved.</p>
        </div>
    </footer>
</body>

</html>