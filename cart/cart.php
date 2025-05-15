<?php
session_start();

// Database connection
$mysqli = new mysqli("localhost", "root", "", "fearofgod");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get low stock items (top 4 lowest stock items)
$low_stock_query = "SELECT product_id FROM product WHERE stock_quantity > 0 ORDER BY stock_quantity ASC LIMIT 4";
$low_stock_result = $mysqli->query($low_stock_query);
$low_stock_items = [];

if ($low_stock_result->num_rows > 0) {
    while ($row = $low_stock_result->fetch_assoc()) {
        $low_stock_items[] = $row['product_id'];
    }
}

// Get or create user's cart
$cart_id = null;
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $user_id = $_SESSION['user_id'];
    $cart_query = "SELECT cart_id FROM cart WHERE user_id = $user_id";
    $cart_result = $mysqli->query($cart_query);

    if ($cart_result->num_rows > 0) {
        $cart = $cart_result->fetch_assoc();
        $cart_id = $cart['cart_id'];
    } else {
        $insert_cart = "INSERT INTO cart (user_id) VALUES ($user_id)";
        if ($mysqli->query($insert_cart)) {
            $cart_id = $mysqli->insert_id;
        }
    }
}

// Handle add to cart requests
if (isset($_GET['add_to_cart']) && is_numeric($_GET['add_to_cart'])) {
    $product_id = (int)$_GET['add_to_cart'];

    if ($cart_id) {
        $check_item = "SELECT * FROM cart_items WHERE cart_id = $cart_id AND product_id = $product_id";
        $item_result = $mysqli->query($check_item);

        if ($item_result->num_rows > 0) {
            $update_qty = "UPDATE cart_items SET quantity = quantity + 1 
                          WHERE cart_id = $cart_id AND product_id = $product_id";
            $mysqli->query($update_qty);
        } else {
            $add_item = "INSERT INTO cart_items (cart_id, product_id, quantity) 
                        VALUES ($cart_id, $product_id, 1)";
            $mysqli->query($add_item);
        }
    } else {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
    }

    header("Location: cart.php");
    exit;
}

// Get cart items with discount calculation
$cart_items = [];
$subtotal = 0;
$total_items = 0;
$discount = 0;
$discount_applied = false;

if ($cart_id) {
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
} elseif (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_query = "SELECT * FROM product WHERE product_id = $product_id";
        $product_result = $mysqli->query($product_query);

        if ($product_result->num_rows > 0) {
            $product = $product_result->fetch_assoc();
            $is_low_stock = in_array($product_id, $low_stock_items);
            $original_price = $product['price'];

            if ($is_low_stock) {
                $discounted_price = $original_price * 0.8;
                $discount += ($original_price - $discounted_price) * $quantity;
                $product['price'] = $discounted_price;
                $discount_applied = true;
            }

            $product['quantity'] = $quantity;
            $product['item_total'] = $product['price'] * $quantity;
            $product['original_price'] = $original_price;
            $product['is_discounted'] = $is_low_stock;
            $cart_items[] = $product;
            $subtotal += $product['item_total'];
            $total_items += $quantity;
        }
    }
}

// Calculate totals
$tax = $subtotal * 0.22;
$shipping = ($subtotal > 100) ? 0 : 15;
$total = $subtotal + $tax + $shipping;

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Fear of God</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="cart.css">
    <link rel="icon" type="image/jpg" href="../assets/itoshiSae.jpg">
    <link rel="stylesheet" href="../fonts/remixicon.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
</head>

<body>
    <nav>
        <div class="logo">
            <a href="../home/home.php">FEAR OF GOD</a>
        </div>
        <ul class="nav-links">
            <li><a href="../home/home.php">Home</a></li>
            <li><a href="../essential/essentials.php">Shop</a></li>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <li><a href="../account/account.php">My Account</a></li>
            <?php endif; ?>
            <li><a href="cart.php">Cart (<?php echo $total_items; ?>)</a></li>
        </ul>
        <div class="login-icon">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <a href="../home/logout.php">Logout</a>
            <?php else: ?>
                <a href="../home/login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="cart-container">
        <div class="cart-header">
            <h1>Shopping Cart</h1>
        </div>

        <?php if (empty($cart_items)): ?>
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added any products to your cart yet.</p>
                <a href="../essential/essentials.php" class="continue-shopping">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="cart-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item <?php echo $item['is_discounted'] ? 'discounted' : ''; ?>">
                            <div class="item-image">
                                <img src="../<?php echo $item["image_url"]; ?>" alt="<?php echo $item["name"]; ?>">
                            </div>
                            <div class="item-details">
                                <h3><?php echo $item["name"]; ?></h3>
                                <p class="item-price">
                                    <?php if ($item['is_discounted']): ?>
                                        <span class="original">$<?php echo number_format($item['original_price'], 2); ?></span>
                                        <span class="discounted">$<?php echo number_format($item['price'], 2); ?></span>
                                        <span class="discount-badge">20% OFF</span>
                                    <?php else: ?>
                                        $<?php echo number_format($item['price'], 2); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="item-quantity">
                                <form action="update_cart.php" method="post" class="quantity-form">
                                    <input type="hidden" name="product_id" value="<?php echo $item["product_id"]; ?>">
                                    <button type="submit" name="action" value="decrease" class="quantity-btn" <?php echo ($item["quantity"] <= 1) ? 'disabled' : ''; ?>>-</button>
                                    <span class="quantity"><?php echo $item["quantity"]; ?></span>
                                    <button type="submit" name="action" value="increase" class="quantity-btn" <?php echo ($item["quantity"] >= $item["stock_quantity"]) ? 'disabled' : ''; ?>>+</button>
                                </form>
                            </div>
                            <div class="item-total">
                                $<?php echo number_format($item["item_total"], 2); ?>
                            </div>
                            <div class="item-remove">
                                <form action="remove_from_cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $item["product_id"]; ?>">
                                    <button type="submit" class="remove-btn"><i class="ri-delete-bin-line"></i></button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h2>Order Summary</h2>

                    <?php if ($discount_applied): ?>
                        <div class="summary-item discount">
                            <span>Discount (20% on low stock items)</span>
                            <span>-$<?php echo number_format($discount, 2); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($subtotal + $discount, 2); ?></span>
                    </div>

                    <?php if ($discount_applied): ?>
                        <div class="summary-item">
                            <span>Discount Applied</span>
                            <span>-$<?php echo number_format($discount, 2); ?></span>
                        </div>

                        <div class="summary-item">
                            <span>Subtotal After Discount</span>
                            <span>$<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="summary-item">
                        <span>IVA (22%)</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>

                    <div class="summary-item">
                        <span>Shipping</span>
                        <span><?php echo ($subtotal > 100) ? 'Free' : '$15.00'; ?></span>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>

                    <form action="checkout.php">
                        <button type="submit" class="checkout-btn">Proceed to Checkout</button>
                    </form>

                    <a href="../essential/essentials.php" class="continue-shopping">Continue Shopping</a>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <div class="copyright">
        <p>© 2025 FEAR OF GOD. All Rights Reserved.</p>
    </div>
</body>

</html>