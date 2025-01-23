<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "MediSync");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert inquiry into the database
    $sql = "INSERT INTO contact_inquiries (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
    if (mysqli_query($conn, $sql)) {
        $success_message = "Thank you for your inquiry. We will get back to you soon!";
    } else {
        $error_message = "There was an error submitting your inquiry. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="ContactUs.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

<section id="contact-us">
    <div class="overlay">
        <div class="contact-form-container">
            <h2>Contact Us</h2>
            <p>If you have any questions or feedback, feel free to reach out to us.</p>

            <?php
            if (isset($success_message)) {
                echo '<div class="success-message">' . $success_message . '</div>';
            }

            if (isset($error_message)) {
                echo '<div class="error-message">' . $error_message . '</div>';
            }
            ?>

            <form method="POST" action="ContactUs.php">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit" class="submit-btn">Send Message</button>
            </form>
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
