<?php
session_start();

// Database connection
$mysqli = new mysqli("localhost", "root", "", "fearofgod");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header("Location: ../home/login.php");
    exit;
}

// Get product ID from URL
if (!isset($_GET['product_id'])) {
    header("Location: essentials.php");
    exit;
}

$product_id = (int)$_GET['product_id'];
$user_id = $_SESSION['user_id'];

// Get or create cart for user
$cart_query = "SELECT cart_id FROM cart WHERE user_id = $user_id";
$cart_result = $mysqli->query($cart_query);

if ($cart_result->num_rows > 0) {
    $cart = $cart_result->fetch_assoc();
    $cart_id = $cart['cart_id'];
} else {
    // Create new cart
    $insert_cart = "INSERT INTO cart (user_id) VALUES ($user_id)";
    if ($mysqli->query($insert_cart)) {
        $cart_id = $mysqli->insert_id;
    } else {
        die("Error creating cart");
    }
}

// Check if product already in cart
$check_item = "SELECT * FROM cart_items WHERE cart_id = $cart_id AND product_id = $product_id";
$item_result = $mysqli->query($check_item);

if ($item_result->num_rows > 0) {
    // Update quantity
    $update_qty = "UPDATE cart_items SET quantity = quantity + 1 
                  WHERE cart_id = $cart_id AND product_id = $product_id";
    $mysqli->query($update_qty);
} else {
    // Add new item
    $add_item = "INSERT INTO cart_items (cart_id, product_id, quantity) 
                VALUES ($cart_id, $product_id, 1)";
    $mysqli->query($add_item);
}

$mysqli->close();

// Redirect back to store page
header("Location: ../essential/essentials.php");
exit;
