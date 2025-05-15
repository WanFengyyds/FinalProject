<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../home/login.php");
    exit;
}

// Database connection
$mysqli = new mysqli("localhost", "root", "", "fearofgod");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$user_id = $_SESSION['user_id'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_address'])) {
        // Add new address
        $stmt = $mysqli->prepare("INSERT INTO shipping_addresses 
                                (user_id, recipient_name, address, city, state, zip_code, country, phone_number, is_default)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $is_default = isset($_POST['is_default']) ? 1 : 0;

        // If setting as default, unset any existing default
        if ($is_default) {
            $mysqli->query("UPDATE shipping_addresses SET is_default = 0 WHERE user_id = $user_id");
        }

        $stmt->bind_param(
            "isssssssi",
            $user_id,
            $_POST['recipient_name'],
            $_POST['address'],
            $_POST['city'],
            $_POST['state'],
            $_POST['zip_code'],
            $_POST['country'],
            $_POST['phone_number'],
            $is_default
        );

        $stmt->execute();
    } elseif (isset($_POST['set_default'])) {
        // Set address as default
        $address_id = (int)$_POST['address_id'];
        $mysqli->query("UPDATE shipping_addresses SET is_default = 0 WHERE user_id = $user_id");
        $mysqli->query("UPDATE shipping_addresses SET is_default = 1 WHERE address_id = $address_id AND user_id = $user_id");
    } elseif (isset($_POST['delete_address'])) {
        // Delete address
        $address_id = (int)$_POST['address_id'];
        $mysqli->query("DELETE FROM shipping_addresses WHERE address_id = $address_id AND user_id = $user_id");
    }

    // Refresh the page
    header("Location: addresses.php");
    exit;
}

// Get user's addresses
$addresses_query = "SELECT * FROM shipping_addresses WHERE user_id = $user_id ORDER BY is_default DESC";
$addresses_result = $mysqli->query($addresses_query);

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Addresses - Fear of God</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="account.css">
    <link rel="icon" type="image/jpg" href="../assets/itoshiSae.jpg">
    <link rel="stylesheet" href="../fonts/remixicon.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
    <style>

    </style>
</head>

<body>
    <nav>
        <div class="logo">
            <a href="../home/home.php">FEAR OF GOD</a>
        </div>
        <ul class="nav-links">
            <li><a href="../home/home.php">Home</a></li>
            <li><a href="../essential/essentials.php">Shop</a></li>
            <li><a href="account.php">My Account</a></li>
            <li><a href="../cart/cart.php">Cart</a></li>
        </ul>
        <div class="login-icon">
            <a href="../home/logout.php">Logout</a>
        </div>
    </nav>

    <div class="account-container">
        <div class="account-header">
            <h1>My Account</h1>
            <p>Manage your addresses</p>
        </div>

        <div class="account-content">
            <div class="account-sidebar">
                <ul>
                    <li><a href="account.php">Account Details</a></li>
                    <li><a href="orders.php">My Orders</a></li>
                    <li><a href="addresses.php" class="active">My Addresses</a></li>
                    <li><a href="../home/logout.php">Logout</a></li>
                </ul>
            </div>

            <div class="addresses-section">
                <div class="section-header">
                    <h2>My Addresses</h2>
                    <button id="toggleFormBtn" class="btn">Add New Address</button>
                </div>

                <?php if ($addresses_result->num_rows > 0): ?>
                    <div class="address-grid">
                        <?php while ($address = $addresses_result->fetch_assoc()): ?>
                            <div class="address-card <?php echo $address['is_default'] ? 'default' : ''; ?>">
                                <?php if ($address['is_default']): ?>
                                    <span class="default-badge">DEFAULT</span>
                                <?php endif; ?>

                                <h3><?php echo htmlspecialchars($address['recipient_name']); ?></h3>
                                <p><?php echo htmlspecialchars($address['address']); ?></p>
                                <p><?php echo htmlspecialchars($address['city']); ?>, <?php echo htmlspecialchars($address['state']); ?> <?php echo htmlspecialchars($address['zip_code']); ?></p>
                                <p><?php echo htmlspecialchars($address['country']); ?></p>
                                <p>Phone: <?php echo htmlspecialchars($address['phone_number']); ?></p>

                                <div class="address-actions">
                                    <?php if (!$address['is_default']): ?>
                                        <form method="POST">
                                            <input type="hidden" name="address_id" value="<?php echo $address['address_id']; ?>">
                                            <button type="submit" name="set_default" class="set-default">Set as Default</button>
                                        </form>
                                    <?php endif; ?>

                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                        <input type="hidden" name="address_id" value="<?php echo $address['address_id']; ?>">
                                        <button type="submit" name="delete_address" class="delete">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>You haven't added any addresses yet.</p>
                <?php endif; ?>

                <div class="add-address-form" id="addAddressForm" style="display: none;">
                    <h3>Add New Address</h3>
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="recipient_name">Recipient Name</label>
                                <input type="text" id="recipient_name" name="recipient_name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" required>
                            </div>

                            <div class="form-group full-width">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="state">State/Province</label>
                                <input type="text" id="state" name="state" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="zip_code">ZIP/Postal Code</label>
                                <input type="text" id="zip_code" name="zip_code" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" id="country" name="country" class="form-control" required>
                            </div>

                            <div class="form-check full-width">
                                <input type="checkbox" id="is_default" name="is_default">
                                <label for="is_default">Set as default shipping address</label>
                            </div>

                            <div class="form-actions full-width">
                                <button type="button" id="cancelFormBtn" class="btn btn-secondary">Cancel</button>
                                <button type="submit" name="add_address" class="btn">Save Address</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright">
        <p>© 2025 FEAR OF GOD. All Rights Reserved.</p>
    </div>

    <script>
        // Toggle add address form
        const toggleFormBtn = document.getElementById('toggleFormBtn');
        const cancelFormBtn = document.getElementById('cancelFormBtn');
        const addAddressForm = document.getElementById('addAddressForm');

        toggleFormBtn.addEventListener('click', function() {
            addAddressForm.style.display = addAddressForm.style.display === 'none' ? 'block' : 'none';
            toggleFormBtn.style.display = addAddressForm.style.display === 'block' ? 'none' : 'block';
        });

        cancelFormBtn.addEventListener('click', function() {
            addAddressForm.style.display = 'none';
            toggleFormBtn.style.display = 'block';
        });
    </script>
</body>

</html>