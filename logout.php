<?php
// Start or resume the user's session.
session_start();
// Remove all session variables.
$_SESSION = [];
// Destroy the current session.
session_destroy();
// Redirect the user to the login page.
header("Location: login.php");
// Stop execution after redirecting.
exit;
// End the PHP section.
?>
