<?php
// Start the session to access the current user
session_start();

// Remove all session variables
session_unset();

// Destroy the session completely
session_destroy();

// Redirect back to the login page
header("Location: login.php");
exit();
?>