<?php
// Start the session.
session_start();
// Include the database connection.
require_once "config/database.php";
// Include the authentication protection function.
require_once "includes/auth.php";
// Require the user to be logged in.
requireLogin();
// Check that the request was submitted using POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Read the submitted post ID as an integer.
    $postId = (int)($_POST["post_id"] ?? 0);
    // Prepare a deletion query containing the owner check.
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    // Delete only the logged-in user's post.
    $stmt->execute([$postId, $_SESSION["user_id"]]);
}
// Return to the feed.
header("Location: index.php");
// Stop execution after redirecting.
exit;
// End the PHP section.
?>
