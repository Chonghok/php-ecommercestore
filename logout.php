<?php
// Start session
session_start();

// Unset all session variables
unset($_SESSION['userID']);
unset($_SESSION['username']);
unset($_SESSION['email']);
unset($_SESSION['logged_in']);

// Redirect to Home page
header("Location: index.php");
exit;
?>