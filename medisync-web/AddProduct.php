<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'];
    $productId = $_POST['id'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "MediSync";
    $con = mysqli_connect($servername, $username, $password, $dbName);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $stmt = $con->prepare("INSERT INTO Cart (Username, Product_id, Quantity) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE Quantity = Quantity + 1");
    $stmt->bind_param("si", $user, $productId);
    $stmt->execute();

    mysqli_close($con);
}
?>
