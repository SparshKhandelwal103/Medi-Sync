<!DOCTYPE html>
<html lang="en"> 
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MediSync - Your Health Companion</title>
  <link rel="stylesheet" href="choice.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

  <!-- Header Section -->
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
          session_start();
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

  <!-- Home Section with Background Image -->
  <section id="home">
    <div class="overlay">
      <h2>HOW MAY I HELP YOU?</h2>
      
      <?php
        if(isset($_SESSION['fname'])){
          echo '<a href="http://127.0.0.1:5000/"><button>
          <span class="transition"></span>
          <span class="gradient"></span>
          <span class="label">I want to know my disease</span>
        </button></a>';
        echo '<br>';
        echo '<br>';
        echo '<a href="http://127.0.0.1:5000/diagnosis"><button>
          <span class="transition"></span>
          <span class="gradient"></span>
          <span class="label">I want to predict drug</span>
        </button></a>';
        }
        else{
          echo '<a href="sign-up.html" class="cta-btn">Get Started</a>';
        }
      ?>
    </div>
  </section>

  <!-- Footer Section -->
  <footer>
    <section id = "contact">
        <p>Email: support@medisync.com | Phone: +91 1234 567 890</p>
        <p>&copy; 2024 MediSync. All Rights Reserved.</p>
    </section>
  </footer>

</body>
</html>
