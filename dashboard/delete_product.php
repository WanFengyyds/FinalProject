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

$product_id = (int)$_GET['id'];

$sql = "DELETE FROM product WHERE product_id = $product_id";
if ($mysqli->query($sql) === TRUE) {
    header("Location: products.php?success=Product deleted successfully");
} else {
    echo "Error deleting product: " . $mysqli->error;
}
$mysqli->close();
exit;
