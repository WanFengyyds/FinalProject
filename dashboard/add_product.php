<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: ../home/login.php");
    exit;
}
$path = " ";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['nomeFile']) && $_FILES['nomeFile']['error'] === UPLOAD_ERR_OK) {
    $nomeFile = $_FILES['nomeFile']['name'];
    //recupero la grandezza di file 
    $size = $_FILES['nomeFile']['size'];
    //recupero il tipo di file
    $type = $_FILES['nomeFile']['type'];
    //recupero tmp_name di file
    $tmp_name = $_FILES['nomeFile']['tmp_name'];

    $dir = "../img";
    move_uploaded_file($_FILES['nomeFile']['tmp_name'], $dir . "/" . $nomeFile);
    $path = "img/" . $nomeFile;

    // Database connection
    $mysqli = new mysqli('localhost', 'root', '', 'fearofgod');

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $image_url = $path;

        // Basic validation
        if (empty($name) || empty($price)) {
            $error = "Name and price are required fields";
        } else {
            // Update product in database
            $sql = "INSERT INTO product (name, description, price, stock_quantity, image_url) 
VALUES ('$name', '$description', '$price', '$stock_quantity', '$image_url')";

            if ($mysqli->query($sql) === TRUE) {
                header("Location: add_product.php?success=Product added successfully");
                exit;
            } else {
                $error = "Error adding product: " . $mysqli->error;
            }
        }
    }

    $mysqli->close();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Fear of God</title>
    <link rel="stylesheet" href="dashboard.css">
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
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <!-- Edit Product Content -->
    <section class="edit-product-container">
        <div class="edit-product-header">
            <h1 class="edit-product-title">Add Product</h1>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>

        <form class="edit-product-form" method="POST" action="add_product.php" enctype="multipart/form-data">
            <input type="hidden" name="product_id">

            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" class="form-control"
                    required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control form-textarea"></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" min="0"
                    required>
            </div>

            <div class="form-group">
                <label for="stock_quantity">Stock Quantity</label>
                <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" min="0"
                    required>
            </div>

            <div class="form-group">
                <label for="nomeFile">Product Image</label>
                <input type="file" id="nomeFile" name="nomeFile" class="form-control" accept="image/*" required>
            </div>

            <div class="btn-group">
                <button type="submit" class="submit-btn">Add Product</button>
        </form>
    </section>


    </div>
    <div class="copyright">
        <p>© 2025 FEAR OF GOD ADMIN DASHBOARD. All Rights Reserved.</p>
    </div>
    </footer>
</body>

</html>