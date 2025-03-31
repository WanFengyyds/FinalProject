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

// Fetch all products
$sql = "SELECT * FROM product";
$result = $mysqli->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Fear of God</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="../fonts/remixicon.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/jpg" href="../assets/itoshiSae.jpg">
</head>

<body>
    <!-- Navigation -->
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

    <!-- Products Content -->
    <section class="products-container">
        <div class="products-header">
            <h1 class="products-title">Product Management</h1>
            <a href="add_product.php" class="add-product-btn">Add New Product</a>
        </div>

        <?php if (!empty($products)): ?>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                            <td>
                                <?php if (!empty($product['image_url'])): ?>
                                    <img src="../<?php echo $product['image_url'] ?>" alt="<?php echo $product['name'] ?>" class="product-image">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo substr($product['description'], 0, length: 50); ?>...</td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['stock_quantity']; ?></td>
                            <td>
                                <button class="action-btn icon-btn" onclick="window.location.href='edit_product.php?id=<?php echo $product['product_id']; ?>'">
                                    <i class="ri-edit-box-line"></i>
                                </button>
                                <button class="action-btn icon-btn" onclick="confirmDelete(<?php echo $product['product_id']; ?>)">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </section>
    <div class="copyright">
        <p>© 2025 FEAR OF GOD ADMIN DASHBOARD. All Rights Reserved.</p>
    </div>
    </footer>

    <script>
        function confirmDelete(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                window.location.href = 'delete_product.php?id=' + productId;
            }
        }
    </script>
</body>

</html>