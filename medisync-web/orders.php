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

// Fetch all orders for the user
$orders_sql = "SELECT * FROM orders WHERE user_email = ? ORDER BY order_date DESC";
$orders_stmt = $conn->prepare($orders_sql);
$orders_stmt->bind_param("s", $user_email);
$orders_stmt->execute();
$orders_result = $orders_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="orders.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>My Orders</title>
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

<section id="orders">
    <div class="overlay">
        <div class="orders-container">
            <h2>Your Orders</h2>
            <?php if ($orders_result->num_rows > 0): ?>
                <?php while ($order = $orders_result->fetch_assoc()): ?>
                    <div class="order-card">
                        <h3>Order ID: <?php echo $order['order_id']; ?></h3>
                        <p><strong>Date:</strong> <?php echo $order['order_date']; ?></p>
                        <p><strong>Total Amount:</strong> ₹<?php echo number_format($order['total_amount'], 2); ?></p>
                        <h4>Items:</h4>
                        <ul>
                            <?php
                            $order_items_sql = "SELECT oi.quantity, oi.price, p.pname 
                                                FROM order_items oi 
                                                JOIN product p ON oi.product_id = p.Product_id 
                                                WHERE oi.order_id = ?";
                            $order_items_stmt = $conn->prepare($order_items_sql);
                            $order_items_stmt->bind_param("i", $order['order_id']);
                            $order_items_stmt->execute();
                            $order_items_result = $order_items_stmt->get_result();

                            while ($item = $order_items_result->fetch_assoc()):
                            ?>
                                <li>
                                    <?php echo htmlspecialchars($item['pname']); ?> - 
                                    Quantity: <?php echo $item['quantity']; ?> - 
                                    Price: ₹<?php echo number_format($item['price'], 2); ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You have not placed any orders yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<footer>
    <section id="contact">
        <p>Email: support@medisync.com | Phone: +91 1234 567 890</p>
        <p>&copy; 2024 MediSync. All Rights Reserved.</p>
    </section>
</footer>
</body>
</html>
