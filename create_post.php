<?php
// Start the session.
session_start();
// Include the database connection.
require_once "config/database.php";
// Include the authentication protection function.
require_once "includes/auth.php";
// Require the user to be logged in.
requireLogin();
// Check that the request was sent using POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Read and trim the submitted post content.
    $content = trim($_POST["content"] ?? "");
    // Check that the post is not empty.
    if ($content !== "") {
        // Prepare the post insertion query.
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        // Insert the post using the logged-in user's ID.
        $stmt->execute([$_SESSION["user_id"], $content]);
    }
}
// Return the user to the feed.
header("Location: index.php");
// Stop execution after redirecting.
exit;
// End the PHP section.
?>
