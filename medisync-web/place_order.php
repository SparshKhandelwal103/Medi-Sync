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

// Fetch user details
$user_sql = "SELECT FName, LName, Phone, City, Address, Pincode FROM userprofile WHERE Email = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("s", $user_email);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch cart items
$cart_sql = "SELECT c.product_id, c.quantity, p.price, p.pname
             FROM cart c
             JOIN product p ON c.product_id = p.Product_id
             WHERE c.user_email = ?";
$cart_stmt = $conn->prepare($cart_sql);
$cart_stmt->bind_param("s", $user_email);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

// Calculate total amount
$total_amount = 0;
$cart_items = [];
while ($row = $cart_result->fetch_assoc()) {
    $total_amount += $row['quantity'] * $row['price'];
    $cart_items[] = $row; // Store cart items for later
}

// Confirm order processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into orders table
        $order_sql = "INSERT INTO orders (user_email, total_amount) VALUES (?, ?)";
        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param("sd", $user_email, $total_amount);
        $order_stmt->execute();
        $order_id = $conn->insert_id; // Get the generated order ID

        // Insert items into order_items table
        $order_item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $order_item_stmt = $conn->prepare($order_item_sql);

        foreach ($cart_items as $item) {
            $order_item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $order_item_stmt->execute();
        }

        // Clear the cart
        $clear_cart_sql = "DELETE FROM cart WHERE user_email = ?";
        $clear_cart_stmt = $conn->prepare($clear_cart_sql);
        $clear_cart_stmt->bind_param("s", $user_email);
        $clear_cart_stmt->execute();

        // Commit transaction
        $conn->commit();

        // Redirect to order confirmation page
        header("Location: order_confirmation.php?order_id=$order_id");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        die("Error placing order: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="place_order.css">
    <title>Confirm Order</title>
</head>
<body>
<header>
    <div class="header-content">
        <h1>Confirm Your Order</h1>
    </div>
</header>

<section id="home">
    <div class="overlay">
        <div class="cart-container">
            <h2>Shipping Details</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['FName'] . ' ' . $user['LName']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['Phone']); ?></p>
            <p><strong>City:</strong> <?php echo htmlspecialchars($user['City']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['Address']); ?></p>
            <p><strong>Pincode:</strong> <?php echo htmlspecialchars($user['Pincode']); ?></p>

            <h3>Order Summary</h3>
            <ul>
                <?php foreach ($cart_items as $item): ?>
                    <li>
                        <?php echo htmlspecialchars($item['pname']); ?> - 
                        Quantity: <?php echo $item['quantity']; ?> - 
                        Price: ₹<?php echo number_format($item['price'], 2); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Total Amount:</strong> ₹<?php echo number_format($total_amount, 2); ?></p>

            <form method="POST">
                <button type="submit" name="confirm_order" class="place-order-btn">Confirm Order</button>
            </form>
        </div>
    </div>
</section>

<footer>
    <p>&copy; 2024 Medisync</p>
</footer>
</body>
</html>
