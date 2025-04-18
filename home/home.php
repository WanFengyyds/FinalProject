<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fear Of God | Premium Essentials</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/jpg" href="../assets/itoshiSae.jpg">
</head>

<body>

    <!-- Login Session -->
    <?php
    session_start();
    // Check login status
    $logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    $username = $logged_in ? $_SESSION['username'] : '';
    ?>

    <!-- Navigation -->
    <nav>
        <div class="logo">
            <a href="home.php">
                FEAR OF GOD
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="../essential/essentials.php">Essentials</a></li>
            <li><a href="#">Collections</a></li>
            <li><a href="#">Accessories</a></li>
            <li><a href="#">About</a></li>
        </ul>
        <div class="login-icon">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="../dashboard/dashboard.php">Dashboard</a> |
                <?php else: ?>
                    <a href="../account/account.php">My Account</a> |
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <div class="collection">ESSENTIALS COLLECTION</div>
            <h1>Timeless Essentials. Effortless Fear.</h1>
            <a href="#" class="btn">Shop Essentials</a>
        </div>
    </section>

    <!-- Last Pieces Section -->
    <section class="trending-products">
        <h2 class="section-title">Last Pieces Available</h2>
        <div class="products-grid">
            <?php
            $mysqli = new mysqli("localhost", "root", "", "fearofgod");
            // Get products with lowest stock quantities (limit to 4 items)
            $sql = "SELECT * FROM product WHERE stock_quantity > 0 ORDER BY stock_quantity ASC LIMIT 4";
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<img src="../' . $row["image_url"] . '" alt="' . $row["name"] . '" class="product-img">';
                    echo '<div class="product-details">';
                    echo '<h3 class="product-name">' . $row["name"] . '</h3>';
                    $originalPrice = $row["price"];
                    $salePrice = $originalPrice * 0.8;  // 20% off
                    echo '<p class="product-price"><s>$' . number_format($originalPrice, decimals: 2) . '</s> <span class="sale-price">$' . number_format($salePrice, 2) . '</span></p>';
                    echo '<button class="add-to-cart">Add to Cart</button>';
                    echo '</div></div>';
                }
            }
            $mysqli->close();
            ?>
        </div>
    </section>

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
</body>

</html>