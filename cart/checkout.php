<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../home/login.php");
    exit;
}

// Database connection
$mysqli = new mysqli("localhost", "root", "", "fearofgod");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$user_id = $_SESSION['user_id'];

// Get user's cart
$cart_query = "SELECT cart_id FROM cart WHERE user_id = $user_id";
$cart_result = $mysqli->query($cart_query);

if ($cart_result->num_rows === 0) {
    header("Location: cart.php");
    exit;
}

$cart = $cart_result->fetch_assoc();
$cart_id = $cart['cart_id'];

// Get cart items with discount calculation (same as cart.php)
$low_stock_query = "SELECT product_id FROM product WHERE stock_quantity > 0 ORDER BY stock_quantity ASC LIMIT 4";
$low_stock_result = $mysqli->query($low_stock_query);
$low_stock_items = [];

if ($low_stock_result->num_rows > 0) {
    while ($row = $low_stock_result->fetch_assoc()) {
        $low_stock_items[] = $row['product_id'];
    }
}

$cart_items = [];
$subtotal = 0;
$total_items = 0;
$discount = 0;
$discount_applied = false;

$items_query = "SELECT p.*, ci.quantity 
               FROM cart_items ci 
               JOIN product p ON ci.product_id = p.product_id 
               WHERE ci.cart_id = $cart_id";
$items_result = $mysqli->query($items_query);

while ($item = $items_result->fetch_assoc()) {
    $is_low_stock = in_array($item['product_id'], $low_stock_items);
    $original_price = $item['price'];

    if ($is_low_stock) {
        $discounted_price = $original_price * 0.8;
        $discount += ($original_price - $discounted_price) * $item['quantity'];
        $item['price'] = $discounted_price;
        $discount_applied = true;
    }

    $item['item_total'] = $item['price'] * $item['quantity'];
    $item['original_price'] = $original_price;
    $item['is_discounted'] = $is_low_stock;
    $cart_items[] = $item;
    $subtotal += $item['item_total'];
    $total_items += $item['quantity'];
}

// Calculate totals
$tax = $subtotal * 0.22;
$shipping = ($subtotal > 100) ? 0 : 15;
$total = $subtotal + $tax + $shipping;

// Get user's shipping address
$address_query = "SELECT * FROM shipping_addresses WHERE user_id = $user_id AND is_default = 1";
$address_result = $mysqli->query($address_query);
$shipping_address = $address_result->fetch_assoc();

// Process checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set default values if not provided
    $same_as_shipping = isset($_POST['same_as_shipping']) ? (bool)$_POST['same_as_shipping'] : true;
    $billing_address = $same_as_shipping ?
        $shipping_address['address'] : ($_POST['billing_address'] ?? '');
    $payment_method = $_POST['payment_method'] ?? 'Credit Card';


    // Create order
    $insert_order = "INSERT INTO orders (user_id, total_amount, status, shipping_address, billing_address, payment_method)
                    VALUES (?, ?, 'Processing', ?, ?, ?)";
    $stmt = $mysqli->prepare($insert_order);

    $stmt->bind_param("idsss", $user_id, $total, $shipping_address['address'], $billing_address, $payment_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    // Add order items
    foreach ($cart_items as $item) {
        $price_to_use = $item['is_discounted'] ? $item['price'] : $item['original_price'];
        $insert_item = "INSERT INTO itemorder (order_id, product_id, quantity, price_at_time_of_purchase)
                       VALUES (?, ?, ?, ?)";
        $item_stmt = $mysqli->prepare($insert_item);
        $item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $price_to_use);
        $item_stmt->execute();
        $item_stmt->close();

        // Update product stock
        $update_stock = "UPDATE product SET stock_quantity = stock_quantity - ? WHERE product_id = ?";
        $stock_stmt = $mysqli->prepare($update_stock);
        $stock_stmt->bind_param("ii", $item['quantity'], $item['product_id']);
        $stock_stmt->execute();
        $stock_stmt->close();
    }

    // Clear cart
    $delete_cart_items = "DELETE FROM cart_items WHERE cart_id = $cart_id";
    $mysqli->query($delete_cart_items);

    header("Location: ../home/home.php");
    exit;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Fear of God</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="cart.css">
    <link rel="icon" type="image/jpg" href="../assets/itoshiSae.jpg">
    <link rel="stylesheet" href="../fonts/remixicon.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
    <style>

    </style>
</head>

<body>
    <nav>
        <div class="logo">
            <a href="../home/home.php">FEAR OF GOD</a>
        </div>
        <ul class="nav-links">
            <li><a href="../home/home.php">Home</a></li>
            <li><a href="../essential/essentials.php">Shop</a></li>
            <li><a href="../account/account.php">My Account</a></li>
            <li><a href="cart.php">Cart (<?php echo $total_items; ?>)</a></li>
        </ul>
        <div class="login-icon">
            <a href="../home/logout.php">Logout</a>
        </div>
    </nav>

    <div class="checkout-container">
        <div class="checkout-header">
            <h1>Checkout</h1>
            <p>Complete your purchase</p>
        </div>

        <form class="checkout-form" method="POST" action="checkout.php">
            <div class="form-section">
                <h2>Shipping Information</h2>
                <?php if ($shipping_address): ?>
                    <div class="form-group">
                        <label>Shipping Address</label>
                        <div style="padding: 12px 15px; background-color: rgba(255,255,255,0.05); border-radius: 4px;">
                            <?php echo htmlspecialchars($shipping_address['recipient_name']); ?><br>
                            <?php echo htmlspecialchars($shipping_address['address']); ?><br>
                            <?php echo htmlspecialchars($shipping_address['city']); ?>,
                            <?php echo htmlspecialchars($shipping_address['state']); ?>
                            <?php echo htmlspecialchars($shipping_address['zip_code']); ?><br>
                            <?php echo htmlspecialchars($shipping_address['country']); ?>
                        </div>
                        <a href="../account/addresses.php" style="display: inline-block; margin-top: 10px; color: #ccc; text-decoration: underline;">Change address</a>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <p>No shipping address found. Please add one in your account.</p>
                        <a href="../account/addresses.php" class="continue-shopping">Add Address</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-section">
                <h2>Billing Information</h2>
                <div class="same-as-shipping">
                    <input type="checkbox" id="same_as_shipping" name="same_as_shipping" checked>
                    <label for="same_as_shipping">Same as shipping address</label>
                </div>
                <div class="form-group" id="billing_address_group" style="display: none;">
                    <label for="billing_address">Billing Address</label>
                    <textarea id="billing_address" name="billing_address" class="form-control" rows="4"></textarea>
                </div>
            </div>

            <div class="form-section">
                <h2>Payment Method</h2>
                <div class="payment-methods">
                    <label class="payment-method selected">
                        <input type="radio" name="payment_method" value="Credit Card" checked>
                        <i class="ri-bank-card-line"></i>
                        Credit Card
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="PayPal">
                        <i class="ri-paypal-line"></i>
                        PayPal
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="Bank Transfer">
                        <i class="ri-exchange-line"></i>
                        Bank Transfer
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="Cash on Delivery">
                        <i class="ri-money-dollar-circle-line"></i>
                        Cash on Delivery
                    </label>
                </div>
            </div>

            <?php if ($shipping_address): ?>
                <button type="submit" class="place-order-btn">PLACE ORDER</button>
            <?php endif; ?>
        </form>

        <div class="order-summary">
            <h2>Order Summary</h2>
            <div class="order-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="order-item">
                        <div class="order-item-name">
                            <?php echo htmlspecialchars($item['name']); ?>
                            <span style="color: #999; font-size: 12px;">× <?php echo $item['quantity']; ?></span>
                        </div>
                        <div class="order-item-price">
                            <?php if ($item['is_discounted']): ?>
                                <span class="original-price">$<?php echo number_format($item['original_price'], 2); ?></span>
                                <span class="discounted-price">$<?php echo number_format($item['price'], 2); ?></span>
                            <?php else: ?>
                                $<?php echo number_format($item['price'], 2); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary-item">
                <span>Subtotal</span>
                <span>$<?php echo number_format($subtotal + $discount, 2); ?></span>
            </div>

            <?php if ($discount_applied): ?>
                <div class="summary-item discount">
                    <span>Discount (20%)</span>
                    <span>-$<?php echo number_format($discount, 2); ?></span>
                </div>
            <?php endif; ?>

            <div class="summary-item">
                <span>IVA (22%)</span>
                <span>$<?php echo number_format($tax, 2); ?></span>
            </div>

            <div class="summary-item">
                <span>Shipping</span>
                <span><?php echo ($shipping > 0) ? '$' . number_format($shipping, 2) : 'FREE'; ?></span>
            </div>

            <div class="summary-total">
                <span>Total</span>
                <span>$<?php echo number_format($total, 2); ?></span>
            </div>

            <a href="cart.php" class="continue-shopping" style="text-align: center; display: block; margin-top: 20px;">Back to Cart</a>
        </div>
    </div>

    <div class="copyright">
        <p>© 2025 FEAR OF GOD. All Rights Reserved.</p>
    </div>

    <script>
        // Toggle billing address field
        document.getElementById('same_as_shipping').addEventListener('change', function() {
            document.getElementById('billing_address_group').style.display = this.checked ? 'none' : 'block';
        });

        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input').checked = true;
            });
        });
    </script>
</body>

</html>