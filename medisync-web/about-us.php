<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - MediSync</title>
  <link rel="stylesheet" href="about-us.css">
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

  <!-- About Us Section -->
  <section id="about">
    <div class="content">
      <h2>About MediSync</h2>
      <p>
        Welcome to MediSync! Our mission is to empower individuals and healthcare professionals with a platform that blends medical expertise and technology. MediSync is a machine learning-driven web platform that assists users in identifying potential health issues based on their symptoms, suggests appropriate medications, and provides preventive advice.
      </p>
      <p>
        Our platform not only helps everyday users in understanding and addressing their health concerns but also supports medical students and doctors in prescribing treatments based on diagnoses. MediSync bridges the gap between advanced technology and the healthcare industry, offering personalized treatment recommendations, preventive healthcare insights, and predictive medical solutions.
      </p>
      <p>
        We believe in making healthcare accessible and personalized, leveraging AI to provide relevant medical information and support to users. With MediSync, managing health becomes simpler and more efficient.
      </p>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services">
    <div class="content">
      <h2>Our Services</h2>
      <div class="service-list">
        <div class="service-item">
          <h3>Symptom Checker</h3>
          <p>Our AI-driven symptom checker helps users identify potential health issues based on the symptoms they report.</p>
        </div>
        <div class="service-item">
          <h3>Medication Suggestions</h3>
          <p>MediSync provides personalized medication suggestions based on diagnosis, making it easy for users to manage minor ailments.</p>
        </div>
        <div class="service-item">
          <h3>Medical Resources for Students</h3>
          <p>Designed with medical students in mind, our platform assists in learning about medications and treatments through AI recommendations.</p>
        </div>
        <div class="service-item">
          <h3>Preventive Healthcare Advice</h3>
          <p>We offer preventive health tips to help users maintain their well-being and reduce the risk of future health issues.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <section id = "contact">
        <p>Email: support@medisync.com | Phone: +91 1234 567 890</p>
        <p>&copy; 2024 MediSync. All Rights Reserved.</p>
    </section>
  </footer>

</body>
</html>
