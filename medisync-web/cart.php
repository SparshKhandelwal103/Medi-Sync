<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "MediSync");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION['Email'])) {
    header("Location: login.html");
    exit();
}

$user_email = $_SESSION['Email'];

// Handle quantity update and delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'], $_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        $action = $_POST['action'];

        if ($action === 'increase') {
            $update_sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_email = ? AND product_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("si", $user_email, $product_id);
            $stmt->execute();
        } elseif ($action === 'decrease') {
            $update_sql = "UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE user_email = ? AND product_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("si", $user_email, $product_id);
            $stmt->execute();
        } elseif ($action === 'delete') {
            $delete_sql = "DELETE FROM cart WHERE user_email = ? AND product_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("si", $user_email, $product_id);
            $stmt->execute();
        }
    }
}

// Fetch cart items
$sql = "SELECT c.product_id, c.quantity, p.pname, p.price, (c.quantity * p.price) AS total_price, p.image
        FROM cart c
        JOIN product p ON c.product_id = p.Product_id
        WHERE c.user_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

$total_amount = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cart.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Cart</title>
</head>
<body>
<header>
    <div class="header-content">
        <div class="logo-container">
            <a href="index.php"><img src="medical care logo template social media .png" alt="MediSync Logo" class="logo"></a>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <a href="choice.php">Ask AI</a>
            <a href="shop.php">Shop</a>
            <a href="about-us.php">About Us</a>
            <a href="ContactUs.php">Contact Us</a>
        </nav>
        <?php
            if (isset($_SESSION['fname'])) {
                echo '<div class="dropdown">
                        <span class="dropdown-toggle">Hello, ' . $_SESSION['fname'] . '</span>
                        <div class="dropdown-menu">
                            <a href="cart.php"><div class="fa fa-shopping-cart"></div>  Cart</a>
                            <a href="orders.php">Orders</a>
                            <a href="LogOut.php">Log Out</a>
                        </div>
                        </div>';
            } else {
                echo '<a href="Login.html" class="signup-btn">Sign In</a>';
            }
        ?>
    </div>
</header>

<section id="home">
    <div class="overlay">
        <div class="cart-container">
    <h2>Your Cart</h2>
    <div class="cart-items">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): 
                $total_amount += $row['total_price'];
            ?>
            <div class="cart-item">
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['pname']); ?>" class="cart-item-image">
                <div class="cart-item-details">
                    <h3><?php echo htmlspecialchars($row['pname']); ?></h3>
                    <p>Price: ₹<?php echo number_format($row['price'], 2); ?></p>
                    <p>Total: ₹<?php echo number_format($row['total_price'], 2); ?></p>
                    <div class="cart-item-actions">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button type="submit" name="action" value="decrease" class="action-btn decrease-btn">-</button>
                        </form>
                        <span><?php echo $row['quantity']; ?></span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button type="submit" name="action" value="increase" class="action-btn increase-btn">+</button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button type="submit" name="action" value="delete" class="action-btn delete-btn">Delete</button>
                        </form>
                    </div> 
                </div>
            </div>
            <?php endwhile; ?>
            
            <!-- Cart Summary Section -->
            <div class="cart-summary">
                <h3>Cart Summary</h3>
                <p>Total Amount: ₹<?php echo number_format($total_amount, 2); ?></p>
                <form action="place_order.php" method="POST">
                    <button type="submit" class="place-order-btn">Place Order</button>
                </form>
            </div>
            
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</div>

    </div>
</section>

<footer>
    <section id = "contact">
        <p>Email: support@medisync.com | Phone: +91 1234 567 890</p>
        <p>&copy; 2024 MediSync. All Rights Reserved.</p>
    </section>
  </footer>
</body>
</html>
