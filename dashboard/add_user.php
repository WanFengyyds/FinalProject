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

// Initialize variables
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['error'] = "All fields are required";
    } elseif (!in_array($role, ['user', 'admin'])) {
        $_SESSION['error'] = "Invalid role selected";
    } else {
        // Check if username or email exists
        $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = $mysqli->query($check_query);

        if ($check_result->num_rows > 0) {
            $existing = $check_result->fetch_assoc();
            if ($existing['username'] == $username) {
                $_SESSION['error'] = "Username already exists!";
            } else {
                $_SESSION['error'] = "Email already exists!";
            }
        } else {
            // Insert new user
            $insert_query = "INSERT INTO users (username, email, pwd, role) 
                           VALUES ('$username', '$email', '$password', '$role')";

            if ($mysqli->query($insert_query)) {
                $_SESSION['success'] = "User added successfully!";
            } else {
                $_SESSION['error'] = "Error adding user: " . $mysqli->error;
            }
        }
    }

    // Redirect to refresh the page
    header("Location: add_user.php");
    exit;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Fear of God</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <a href="home.php">FEAR OF GOD</a>
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

    <!-- Edit Product Content -->
    <section class="edit-product-container">
        <div class="edit-product-header">
            <h1 class="edit-product-title">Add User</h1>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>

        <?php
        if (isset($_SESSION['error'])) {
            echo "<script>alert('" . $_SESSION['error'] . "');</script>";
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            echo "<script>alert('" . $_SESSION['success'] . "');</script>";
            unset($_SESSION['success']);
        }
        ?>

        <form class="edit-product-form" method="POST" action="add_user.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="">Select Role</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="submit-btn">Add User</button>
            </div>
        </form>
    </section>


    </div>
    <div class="copyright">
        <p>© 2025 FEAR OF GOD ADMIN DASHBOARD. All Rights Reserved.</p>
    </div>
    </footer>
</body>

</html>