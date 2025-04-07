<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../home/login.php");
    exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'fearofgod');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Get current user data
$user_result = $mysqli->query("SELECT username, email FROM users WHERE user_id = $user_id");
$user = $user_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($new_username) || empty($new_email)) {
        $error = "Username and email are required fields.";
    } else {
        // Check if username exists (excluding current user)
        $check_username = $mysqli->query("SELECT user_id FROM users WHERE username = '$new_username' AND user_id != $user_id");
        if ($check_username->num_rows > 0) {
            $error = "Username already exists.";
        }

        // Check if email exists (excluding current user)
        $check_email = $mysqli->query("SELECT user_id FROM users WHERE email = '$new_email' AND user_id != $user_id");
        if ($check_email->num_rows > 0) {
            $error = "Email already exists.";
        }

        // If password is provided, validate it
        if (!empty($new_password)) {
            if (strlen($new_password) < 6) {
                $error = "Password must be at least 6 characters long.";
            } elseif ($new_password !== $confirm_password) {
                $error = "Passwords do not match.";
            }
        }

        // If no errors, update the profile
        if (empty($error)) {
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET 
                        username = '$new_username',
                        email = '$new_email',
                        password = '$hashed_password'
                        WHERE user_id = $user_id";
            } else {
                $sql = "UPDATE users SET 
                        username = '$new_username',
                        email = '$new_email'
                        WHERE user_id = $user_id";
            }

            if ($mysqli->query($sql)) {
                $message = "Profile updated successfully!";
                $_SESSION['username'] = $new_username;
            } else {
                $error = "Error updating profile.";
            }
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
    <title>Edit Profile - Fear of God</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="account.css">
    <link rel="icon" type="image/jpg" href="../assets/itoshiSae.jpg">
</head>

<body>
    <nav>
        <div class="logo">
            <a href="../home/home.php">FEAR OF GOD</a>
        </div>
        <ul class="nav-links">
            <li><a href="../home/home.php">Home</a></li>
            <li><a href="../shop/shop.php">Shop</a></li>
            <li><a href="account.php">My Account</a></li>
            <li><a href="../cart/cart.php">Cart</a></li>
        </ul>
        <div class="login-icon">
            <a href="../home/logout.php">Logout</a>
        </div>
    </nav>

    <section class="user-account">
        <div class="account-header">
            <h1>Edit Profile</h1>
        </div>

        <div class="edit-profile-form">
            <?php if (!empty($message)): ?>
                <div class="success-message"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">New Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                </div>

                <div class="btn-group">
                    <button type="submit" class="submit-btn">Update Profile</button>
                    <a href="account.php" class="submit-btn">Cancel</a>
                </div>
            </form>
        </div>
    </section>

    <div class="copyright">
        <p>© 2025 FEAR OF GOD. All Rights Reserved.</p>
    </div>
</body>

</html>