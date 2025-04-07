<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: ../home/login.php");
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'fearofgod');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Initialize variables
$user = [
    'user_id' => '',
    'username' => '',
    'email' => '',
    'role' => '',
];
$error = '';

// Check if user ID is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user data
    $sql = "SELECT user_id, username, email, role FROM users WHERE user_id = '$user_id'";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $error = "User not found";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Check if username already exists
    $check_username = $mysqli->query("SELECT user_id FROM users WHERE username = '$username' AND user_id != '$user_id'");
    $check_email = $mysqli->query("SELECT user_id FROM users WHERE email = '$email' AND user_id != '$user_id'");

    if ($check_username->num_rows > 0) {
        $error = "Username already exists";
        $sql = "SELECT user_id, username, email, role FROM users WHERE user_id = '$user_id'";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
        }
    } elseif ($check_email->num_rows > 0) {
        $error = "Email already exists";
        $sql = "SELECT user_id, username, email, role FROM users WHERE user_id = '$user_id'";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
        }
    } elseif (empty($username) || empty($email)) {
        $error = "Username and email are required fields";
    } else {
        // Update user in database
        if (!empty($password)) {
            $sql = "UPDATE users SET 
                    username = '$username',
                    email = '$email',
                    role = '$role',
                    pwd = '$password'
                    WHERE user_id = '$user_id'";
        } else {
            $sql = "UPDATE users SET 
                    username = '$username',
                    email = '$email',
                    role = '$role'
                    WHERE user_id = '$user_id'";
        }

        if ($mysqli->query($sql) === TRUE) {
            echo "<script>alert('User updated successfully'); window.location.href='users.php?success=User updated successfully';</script>";
            exit;
        } else {
            $error = "Error updating user: " . $mysqli->error;
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
    <title>Edit User - Fear of God</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/jpg" href="../assets/itoshiSae.jpg">
</head>

<body>
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

    <section class="edit-product-container">
        <div class="edit-product-header">
            <h1 class="edit-product-title">Edit User</h1>
            <a href="users.php" class="back-btn">← Back to Users</a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form class="edit-product-form" method="POST" action="edit_user.php">
            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control"
                    value="<?php echo $user['username']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="<?php echo $user['email']; ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" class="form-control">
                    <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">New Password (leave blank to keep current)</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>

            <div class="btn-group">
                <button type="submit" class="submit-btn">Update User</button>
                <a href="users.php" class="submit-btn">Cancel</a>
            </div>
        </form>
    </section>

    <div class="copyright">
        <p>© 2025 FEAR OF GOD ADMIN DASHBOARD. All Rights Reserved.</p>
    </div>
</body>

</html>
</div>