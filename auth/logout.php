<?php
// Start the session
session_start();

// Destroy all session data to log the user out
session_unset();
session_destroy();

// Redirect the user to the login page
header("Location: ../auth/login.php");
exit;
?>
