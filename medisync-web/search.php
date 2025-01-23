<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "MediSync");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the search query
$query = isset($_POST['query']) ? $_POST['query'] : '';

if (!empty($query)) {
    $query = mysqli_real_escape_string($conn, $query);

    // Fetch matching products
    $sql = "SELECT pname FROM product WHERE pname LIKE '%$query%' LIMIT 10";
    $result = mysqli_query($conn, $sql);

    $suggestions = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $suggestions[] = $row['pname'];
        }
    }

    // Return JSON response
    echo json_encode($suggestions);
}
?>
    