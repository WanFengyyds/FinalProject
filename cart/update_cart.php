<?php
session_start();

// Database connection
$mysqli = new mysqli("localhost", "root", "", "fearofgod");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_POST['product_id']) && isset($_POST['action'])) {
    $product_id = (int)$_POST['product_id'];
    $action = $_POST['action'];

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        // Update cart in database for logged in users
        $user_id = $_SESSION['user_id'];
        $cart_query = "SELECT cart_id FROM cart WHERE user_id = $user_id";
        $cart_result = $mysqli->query($cart_query);

        if ($cart_result->num_rows > 0) {
            $cart = $cart_result->fetch_assoc();
            $cart_id = $cart['cart_id'];

            // Get current quantity
            $qty_query = "SELECT quantity FROM cart_items 
                         WHERE cart_id = $cart_id AND product_id = $product_id";
            $qty_result = $mysqli->query($qty_query);

            if ($qty_result->num_rows > 0) {
                $item = $qty_result->fetch_assoc();
                $new_qty = $item['quantity'];

                if ($action === 'increase') {
                    $new_qty++;
                } elseif ($action === 'decrease' && $new_qty > 1) {
                    $new_qty--;
                }

                // Update quantity in database
                $update_query = "UPDATE cart_items SET quantity = $new_qty 
                               WHERE cart_id = $cart_id AND product_id = $product_id";
                $mysqli->query($update_query);
            }
        }
    } else {
        // Update session cart for guests
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            if ($action === 'increase') {
                $_SESSION['cart'][$product_id]++;
            } elseif ($action === 'decrease' && $_SESSION['cart'][$product_id] > 1) {
                $_SESSION['cart'][$product_id]--;
            }
        }
    }
}

$mysqli->close();
header("Location: cart.php");
exit;
