<?php
session_start();

// Destroy the session
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session itself

// Redirect to login page
header("Location: login.php");
exit();
?>
