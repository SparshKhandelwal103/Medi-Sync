<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "MediSync");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search term from the query string
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$products = getProducts($searchTerm);
$allproducts = getallProducts();

function getProducts($search = '') {
    $conn = mysqli_connect("localhost", "root", "", "MediSync");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // If a search term exists, query for matching products
    if (!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $sql = "
            SELECT * FROM product 
            WHERE pname LIKE '%$search%' 
            ORDER BY 
                CASE WHEN pname LIKE '$search%' THEN 1
                     WHEN pname LIKE '%$search%' THEN 2
                     ELSE 3
                END, 
                pname ASC
            LIMIT 1"; // Limit to only one product for precise display
    } else {
        // Default: return no products if no search is performed
        $sql = "SELECT * FROM product WHERE 1=0";
    }

    $query = mysqli_query($conn, $sql);

    $products = [];
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $products[] = $row;
        }
    }
    return $products;
}

function getallProducts() {
    $conn = mysqli_connect("localhost", "root", "", "MediSync");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch all products
    $sql = "SELECT * FROM product ORDER BY pname ASC";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        $products = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $products[] = $row;
        }
        return $products;
    } else {
        return [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediSync - Shop</title>
    <link rel="stylesheet" href="shop.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#search-box').on('input', function() {
            let query = $(this).val();

            if (query.length > 1) {
                $.ajax({
                    url: 'search.php',
                    type: 'POST',
                    data: { query: query },
                    success: function(data) {
                        let suggestions = JSON.parse(data);
                        let suggestionBox = $('#suggestions');
                        suggestionBox.empty();

                        if (suggestions.length > 0) {
                            suggestions.forEach(function(item) {
                                suggestionBox.append('<div class="suggestion-item">' + item + '</div>');
                            });

                            // On click, populate the search box and submit the form
                            $('.suggestion-item').on('click', function() {
                                $('#search-box').val($(this).text());
                                $('form').submit();
                            });
                        }
                    }
                });
            } else {
                $('#suggestions').empty(); // Clear suggestions when input is less than 2 characters
            }
        });

        // Hide suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-container').length) {
                $('#suggestions').empty();
            }
        });
    });

    function addToCart(email, productId, action) {
        if (!email) {
            // Redirect unauthenticated users to login
            alert("Please login to continue.");
            return;
        }

        // Use AJAX to update the cart dynamically
        $.ajax({
            url: 'update-cart.php',
            type: 'POST',
            data: { email: email, productId: productId, action: action },
            success: function(data) {
                const response = JSON.parse(data);
                if (response.success) {
                    if (response.quantity === 0) {
                        alert(response.message); // Show alert that product was removed
                        location.reload(); // Refresh the page to show the "Add to Cart" button again
                    } else {
                        // Otherwise, just reload to reflect updated quantity
                        location.reload(); // Refresh to show updated quantity
                    }
                } else {
                    alert("Error updating cart: " + response.message);
                }
            }
        });
    }
    </script>
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
    
    <!-- Search Bar -->
    <div class="search-container">
        <form method="GET" action="shop.php">
            <input 
                type="text" 
                name="search" 
                id="search-box" 
                placeholder="Search for medicines..." 
                value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
            <div id="suggestions" class="suggestion-box"></div>
        </form>
    </div>

    <h1>MEDICINE STORE</h1>
    <div class="small-heading">
        <p>We care. We help.</p> 
    </div>

    <section class="shop-container">
        <div class="products">
            <?php
            if (!empty($products)) {
                foreach ($products as $product) {
                    $productId = $product['Product_id'];
                    $imgPath = $product['Image'];
                    $productName = $product['pname'];
                    $productPrice = $product['price'];
                    $productDescription = $product['Description'];
            
                    echo '<div class="product-card">';
                    echo '<img src="' . $imgPath . '" alt="' . $productName . '">';
                    echo '<div class="product-info">';
                    echo '<h2>' . $productName . '</h2>';
                    echo '<p>' . nl2br($productDescription) . '</p>';
                    echo '<p class="price">₹' . $productPrice . '</p>';
            
                    // Check if the user is logged in and has added the product to the cart
                    if (isset($_SESSION['Email'])) {
                        // Query to get the current quantity from the cart
                        $email = $_SESSION['Email'];
                        $sql = "SELECT quantity FROM cart WHERE user_email = '$email' AND product_id = '$productId'";
                        $result = mysqli_query($conn, $sql);
            
                        if (mysqli_num_rows($result) > 0) {
                            // Product already in the cart
                            $row = mysqli_fetch_assoc($result);
                            $quantity = $row['quantity'];
            
                            echo '<div class="cart-actions">';
                            echo '<button onclick="addToCart(\'' . $email . '\', ' . $productId . ', \'decrease\')" class="action-btn decrease-btn">-</button>';
                            echo '<span id="cart-quantity-' . $productId . '">' . $quantity . '</span>';
                            echo '<button onclick="addToCart(\'' . $email . '\', ' . $productId . ', \'increase\')" class="action-btn increase-btn">+</button>';
                            echo '</div>';
                        } else {
                            // Product not in the cart, show "Add to Cart" button
                            echo '<button class="add-to-cart" onclick="addToCart(\'' . $email . '\', ' . $productId . ', \'add\')">Add to Cart</button>';
                        }
                    } else {
                        // If the user is not logged in, show "Add to Cart"
                        echo '<button class="add-to-cart" onclick="addToCart(null, ' . $productId . ', \'add\')">Add to Cart</button>';
                    }
            
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                foreach ($allproducts as $product) {
                    $productId = $product['Product_id'];
                    $imgPath = $product['Image'];
                    $productName = $product['pname'];
                    $productPrice = $product['price'];
                    $productDescription = $product['Description'];
            
                    echo '<div class="product-card">';
                    echo '<img src="' . $imgPath . '" alt="' . $productName . '">';
                    echo '<div class="product-info">';
                    echo '<h2>' . $productName . '</h2>';
                    echo '<p>' . nl2br($productDescription) . '</p>';
                    echo '<p class="price">₹' . $productPrice . '</p>';
            
                    // Check if the user is logged in and has added the product to the cart
                    if (isset($_SESSION['Email'])) {
                        // Query to get the current quantity from the cart
                        $email = $_SESSION['Email'];
                        $sql = "SELECT quantity FROM cart WHERE user_email = '$email' AND product_id = '$productId'";
                        $result = mysqli_query($conn, $sql);
            
                        if (mysqli_num_rows($result) > 0) {
                            // Product already in the cart
                            $row = mysqli_fetch_assoc($result);
                            $quantity = $row['quantity'];
            
                            echo '<div class="cart-actions">';
                            echo '<button onclick="addToCart(\'' . $email . '\', ' . $productId . ', \'decrease\')" class="action-btn decrease-btn">-</button>';
                            echo '<span id="cart-quantity-' . $productId . '">' . $quantity . '</span>';
                            echo '<button onclick="addToCart(\'' . $email . '\', ' . $productId . ', \'increase\')" class="action-btn increase-btn">+</button>';
                            echo '</div>';
                        } else {
                            // Product not in the cart, show "Add to Cart" button
                            echo '<button class="add-to-cart" onclick="addToCart(\'' . $email . '\', ' . $productId . ', \'add\')">Add to Cart</button>';
                        }
                    } else {
                        // If the user is not logged in, show "Add to Cart"
                        echo '<button class="add-to-cart" onclick="addToCart(null, ' . $productId . ', \'add\')">Add to Cart</button>';
                    }
            
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 MediSync. All rights reserved.</p>
    </footer>
</body>
</html>
