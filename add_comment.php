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
    // Read the post ID as an integer.
    $postId = (int)($_POST["post_id"] ?? 0);
    // Read and trim the comment text.
    $content = trim($_POST["content"] ?? "");
    // Validate that the comment is not empty.
    if ($postId > 0 && $content !== "") {
        // Check that the referenced post exists.
        $check = $pdo->prepare("SELECT id FROM posts WHERE id = ?");
        // Execute the post existence check.
        $check->execute([$postId]);
        // Continue only when the post exists.
        if ($check->fetch()) {
            // Prepare the comment insertion query.
            $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
            // Insert the comment using the logged-in user's ID.
            $stmt->execute([$postId, $_SESSION["user_id"], $content]);
        }
    }
}
// Return to the feed.
header("Location: index.php");
// Stop execution after redirecting.
exit;
// End the PHP section.
?>
