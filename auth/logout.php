<?php
// Start the session so the server knows which user to log out
session_start();

// Remove all session variables
$_SESSION = array();

// Destroy the entire session
session_destroy();

// Redirect the user back to the login page
header("Location: login.php");
exit();
?>