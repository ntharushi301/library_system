<?php

// Start the current session
session_start();

// Destroy all session data and log out the user
session_destroy();

// Redirect the user to the login page
header("Location: /library_system/auth/login.php");

// Stop further script execution
exit();

?>