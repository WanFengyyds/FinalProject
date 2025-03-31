<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'fearofgod');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$product_id = (int)$_GET['id'];

// Delete user
$stmt = $mysqli->prepare("DELETE FROM product WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();

// Redirect back to users page
header("Location: products.php");
$stmt->close();
$mysqli->close();
exit;
