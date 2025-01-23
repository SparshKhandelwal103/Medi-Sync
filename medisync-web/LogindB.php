<?php
session_start(); // Initialize session at the very top

$servername = "localhost";
$username = "root";
$password = "";
$dB = "MediSync";

// Connect to the database
$con = mysqli_connect($servername, $username, $password, $dB);
if (!$con) {
    die("Could not connect to the database: " . mysqli_connect_error());
}

// Get user input
$email = $_POST["Email"];
$password = $_POST["Password"];

// Query to check if the user exists
$check = "SELECT * FROM userprofile WHERE Email = '$email'";

$result = $con->query($check);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $storedPassword = $row["Password"];

    // Validate the password
    if ($password === $storedPassword) {
        // Store user data in session
        $_SESSION['Email'] = $row['Email'];
        $_SESSION['fname'] = $row['FName'];
        $_SESSION['lname'] = $row['LName'];
        $_SESSION['phone'] = $row['Phone'];
        $_SESSION['pincode'] = $row['Pincode'];
        $_SESSION['address'] = $row['Address'];
        $_SESSION['city'] = $row['City'];

        // Redirect to shop.php or index.php
        header("Location: shop.php");
        exit();
    } else {
        $message = "Incorrect Password";
        echo "<script>alert('$message');</script>";
        echo "<script>window.location.href = 'login.html';</script>";
    }
} else {
    $message = "Account not found";
    echo "<script>alert('$message');</script>";
    echo "<script>window.location.href = 'login.html';</script>";
}

// Close the database connection
mysqli_close($con);
?>
