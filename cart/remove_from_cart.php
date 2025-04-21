<?php
session_start();

// Database connection
$mysqli = new mysqli("localhost", "root", "", "fearofgod");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        // Remove from database cart for logged in users
        $user_id = $_SESSION['user_id'];
        $cart_query = "SELECT cart_id FROM cart WHERE user_id = $user_id";
        $cart_result = $mysqli->query($cart_query);

        if ($cart_result->num_rows > 0) {
            $cart = $cart_result->fetch_assoc();
            $cart_id = $cart['cart_id'];

            $delete_query = "DELETE FROM cart_items 
                           WHERE cart_id = $cart_id AND product_id = $product_id";
            $mysqli->query($delete_query);
        }
    } else {
        // Remove from session cart for guests
        if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

$mysqli->close();
header("Location: cart.php");
exit;
