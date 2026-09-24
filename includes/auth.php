<?php
// Start a session if one is not already active.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create a function that protects pages requiring authentication.
function requireLogin() {
    // Check whether the user's ID is missing from the session.
    if (!isset($_SESSION["user_id"])) {
        // Send unauthenticated users to the login page.
        header("Location: login.php");
        // Stop execution after the redirect.
        exit;
    }
}
// End the PHP section of this file.
?>