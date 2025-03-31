<?php
session_start();

// Database connection
$mysqli = new mysqli("localhost", "root", "", "fearofgod");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get all categories for filter
$categories = [];
$result = $mysqli->query("SELECT * FROM category");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[$row['category_id']] = $row['category_name'];
    }
}

// Get products based on filter (if any)
$current_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sql = "SELECT product.* FROM product";
if ($current_category != 0) {
    $sql .= " JOIN productcategory ON product.product_id = productcategory.product_id 
              WHERE productcategory.category_id = " . $current_category;
}
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fear Of God | Premium Essentials</title>
    <link rel="stylesheet" href="essential.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../fonts/remixicon.css">
    <link href="https://fonts.googleapis.com/css?family=Arvo&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <a href="../home/home.php">
                FEAR OF GOD
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="../home/home.php">Home</a></li>
            <li><a href="#">Essentials</a></li>
            <li><a href="#">Collections</a></li>
            <li><a href="#">Accessories</a></li>
            <li><a href="#">About</a></li>
        </ul>
        <div class="login-icon">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="../dashboard/dashboard.php">Dashboard</a> |
                <?php else: ?>
                    <a href="account.php">My Account</a> |
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="essentials-header">
        <h1 class="essentials-title">ESSENTIALS COLLECTION</h1>
        <p>Discover our premium essentials collection</p>
    </section>

    <!-- Filter Section -->
    <div class="filter-container">
    <div class="products-count">
        <p>
            <?php echo $result->num_rows; ?> products found
            <?php if ($current_category > 0 && isset($categories[$current_category])): ?>
                in "<?php echo $categories[$current_category]; ?>"
            <?php endif; ?>
        </p>
    </div>
    <div class="filter-icon">
        <div class="filter-btn" id="filterBtn">
            <i class="ri-equalizer-line"></i>
        </div>
        <ul class="filter-dropdown">
            <li><a href="essentials.php">All</a></li>
            <?php foreach ($categories as $id => $name): ?>
                <li><a href="essentials.php?category=<?php echo $id; ?>"><?php echo $name; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
        </div>

    <!-- Products Grid -->
    <section class="products-grid-container">
        <div class="products-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="../<?php echo $row["image_url"]; ?>" alt="<?php echo $row["name"]; ?>" class="product-img">
                        <div class="product-details">
                            <h3 class="product-name"><?php echo $row["name"]; ?></h3>
                            <p class="product-price">$<?php echo number_format($row["price"], 2); ?></p>
                            <button class="add-to-cart">Add to Cart</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-products">No products found in this category.</p>
            <?php endif; ?>
        </div>
    </section>

</body>

</html>
<?php
$mysqli->close();
?>