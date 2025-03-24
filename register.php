<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Fear of God</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your existing CSS file -->
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <a href="index.html">
                FEAR OF GOD
            </a>       
        </div>
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="#">Essentials</a></li>
            <li><a href="#">Collections</a></li>
            <li><a href="#">Accessories</a></li>
            <li><a href="#">Lookbook</a></li>
            <li><a href="#">About</a></li>
        </ul>
        <div class="login-icon">
            <a href="login.html">Login</a>
        </div>
    </nav>

    <!-- Register Section -->
    <section class="register-section">
        <div class="register-container">
            <h2>Create Your Account</h2>
            <form class="register-form" action="register.php" method="POST" onsubmit="return controlloPassword()">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <button type="button" id="togglePassword" class="show-password-btn">👁️</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
                        <button type="button" id="toggleConfirmPassword" class="show-password-btn">👁️</button>
                    </div>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
            <p class="login-link">Already have an account? <a href="login.html">Login here</a>.</p>
        </div>
    </section>

    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $mysqli = new mysqli('localhost', 'root', '', 'fearofgod');

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    
    // Generate a username from email
    $username = strstr($email, '@', true);
    
    // Direct INSERT without hashing
    $sql = "INSERT INTO users (username, email, pwd, role) VALUES ('$username', '$email', '$password', 'user')";
    
    if ($mysqli->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error during registration: " . $mysqli->error . "');</script>";
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
                    <li><a href="#">Essentials</a></li>
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

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the button text/icon
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });

        toggleConfirmPassword.addEventListener('click', function () {
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