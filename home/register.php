<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Fear of God</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/jpg" href="../assets/itoshiSae.jpg">
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <a href="home.php">
                FEAR OF GOD
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="#">Essentials</a></li>
            <li><a href="#">Collections</a></li>
            <li><a href="#">Accessories</a></li>
            <li><a href="#">Lookbook</a></li>
            <li><a href="#">About</a></li>
        </ul>
        <div class="login-icon">
            <a href="login.php">Login</a>
        </div>
    </nav>

    <!-- Register Section -->
    <section class="login-section">
        <div class="login-container">
            <h2>Create Your Account</h2>
            <form class="login-form" action="register.php" method="POST" onsubmit="return controlloPassword()">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    <span id="username-error" style="color: red; display: none;">Username already exists</span>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <button type="button" id="togglePassword" class="show-password-btn">👁️</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
                        <button type="button" id="toggleConfirmPassword" class="show-password-btn">👁️</button>
                    </div>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
            <p class="register-link">Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </section>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = $_POST['email'];
        $password = md5($_POST['password']); // Hashing the password
        $password = $_POST['password'];
        $username = $_POST['username'];

        $mysqli = new mysqli('localhost', 'root', '', 'fearofgod');

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Check if username or email exists
        $check_query = "SELECT username, email FROM users WHERE username = '$username' OR email = '$email'";
        $result = $mysqli->query($check_query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['username'] === $username) {
                echo "<script>
                    alert('Username already exists. Please choose another one.');
                    document.getElementById('username').value = '';
                </script>";
            } else {
                echo "<script>
                    alert('Email already registered. Please use another email.');
                    document.getElementById('email').value = '';
                </script>";
            }
        } else {
            // Proceed with registration
            $sql = "INSERT INTO users (username, email, pwd, role) VALUES ('$username', '$email', '$password', 'user')";

            if ($mysqli->query($sql)) {
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error during registration: " . $mysqli->error . "');</script>";
            }
        }
        $mysqli->close();
    }
    ?>

    <!-- Footer -->
    <footer>
        <div class="footer-grid">
            <div class="footer-column">
                <h4>Shop</h4>
                <ul>
                    <li><a href="../essential/essentials.php">Essentials</a></li>
                    <li><a href="#">Main Line</a></li>
                    <li><a href="#">Accessories</a></li>
                    <li><a href="#">New Arrivals</a></li>
                    <li><a href="#">Collaborations</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Customer Service</h4>
                <ul>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Shipping Policy</a></li>
                    <li><a href="#">Returns & Exchanges</a></li>
                    <li><a href="#">FAQs</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>About Us</h4>
                <ul>
                    <li><a href="#">Our Story</a></li>
                    <li><a href="#">Sustainability</a></li>
                    <li><a href="#">Lookbooks</a></li>
                    <li><a href="#">Stockists</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>Connect With Us</h4>
                <ul>
                    <li><a href="https://www.instagram.com/fearofgod/">Instagram</a></li>
                    <li><a href="https://www.facebook.com/p/FEAR-OF-GOD-100044264567702/">Facebook</a></li>
                    <li><a href="https://x.com/fearofgod">Twitter</a></li>
                    <li><a href="https://www.pinterest.com/hornbeck/fear-of-god/">Pinterest</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>© 2025 FEAR OF GOD. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- JavaScript for Show Password -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirm-password');

        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the button text/icon
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });

        toggleConfirmPassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);

            // Toggle the button text/icon
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });

        function controlloPassword() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm-password').value;
            if (password != confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>