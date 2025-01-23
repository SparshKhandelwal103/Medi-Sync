<?php
    session_start();
    if (isset($_SESSION['fname'])) {
        session_destroy();
        
        // Check if there is a referrer and use it for redirection, else default to 'index.php'
        $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        
        echo "<script>location.href='$redirectUrl';</script>";
    }
?>
