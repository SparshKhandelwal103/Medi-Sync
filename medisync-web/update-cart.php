<?php
session_start();

// Ensure that the user is logged in
if (!isset($_SESSION['Email'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to continue.']);
    exit;
}

$email = $_SESSION['Email'];
$productId = $_POST['productId'];
$action = $_POST['action'];

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "MediSync");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the current quantity from the cart
$sql = "SELECT quantity FROM cart WHERE user_email = '$email' AND product_id = '$productId'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $currentQuantity = $row['quantity'];

    // Update quantity based on action
    if ($action == 'increase') {
        $newQuantity = $currentQuantity + 1;
    } elseif ($action == 'decrease') {
        // Decrease quantity, but not below 0
        if ($currentQuantity > 1) {
            $newQuantity = $currentQuantity - 1;
        } else {
            // If quantity is 1, set it to 0
            $newQuantity = 0;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        exit;
    }

    // If quantity is 0, delete the row from the cart
    if ($newQuantity == 0) {
        $deleteSql = "DELETE FROM cart WHERE user_email = '$email' AND product_id = '$productId'";
        if (mysqli_query($conn, $deleteSql)) {
            echo json_encode(['success' => true, 'message' => 'Product removed from cart', 'quantity' => $newQuantity]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove product from cart.']);
        }
    } else {
        // Otherwise, update the cart with the new quantity
        $updateSql = "UPDATE cart SET quantity = $newQuantity WHERE user_email = '$email' AND product_id = '$productId'";
        if (mysqli_query($conn, $updateSql)) {
            echo json_encode(['success' => true, 'quantity' => $newQuantity]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update cart.']);
        }
    }
} else {
    // If the product is not in the cart, add it
    if ($action == 'add') {
        $newQuantity = 1; // First time adding to cart
        $insertSql = "INSERT INTO cart (user_email, product_id, quantity) VALUES ('$email', '$productId', $newQuantity)";
        if (mysqli_query($conn, $insertSql)) {
            echo json_encode(['success' => true, 'quantity' => $newQuantity]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add to cart.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not in cart.']);
    }
}

mysqli_close($conn);
?>
