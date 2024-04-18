<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page you want
    header("Location: index.php");
    exit;
} else {   
    // Redirect to the login page or any other page you want
    header("Location: index.php");
    exit;
}
?>