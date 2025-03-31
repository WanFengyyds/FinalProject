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

$user_id = (int)$_GET['id'];
$current_user_id = (int)$_SESSION['user_id'];

// Prevent deleting own account
if ($user_id === $current_user_id) {
    echo "<script>
        alert('You cannot delete your own account!');
        window.location.href = 'users.php';
    </script>";
    exit;
}

// Delete user
$stmt = $mysqli->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Redirect back to users page
exit;
