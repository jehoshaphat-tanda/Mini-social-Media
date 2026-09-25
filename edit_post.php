<?php
// Start the session.
session_start();
// Include the database connection.
require_once "config/database.php";
// Include the authentication protection function.
require_once "includes/auth.php";
// Require the user to be logged in.
requireLogin();
// Read the post ID from the URL and convert it to an integer.
$postId = (int)($_GET["id"] ?? 0);
// Prepare a query that retrieves only the requested user's post.
$stmt = $pdo->prepare("SELECT id, content FROM posts WHERE id = ? AND user_id = ?");
// Execute the ownership check.
$stmt->execute([$postId, $_SESSION["user_id"]]);
// Fetch the post.
$post = $stmt->fetch();
// Stop if the post does not exist or does not belong to the user.
if (!$post) {
    // Return to the feed when ownership validation fails.
    header("Location: index.php");
    // Stop execution after redirecting.
    exit;
}
// Create an error variable.
$error = "";
// Check whether the edit form was submitted.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Read and trim the new post content.
    $content = trim($_POST["content"] ?? "");
    // Validate the edited content.
    if ($content === "") {
        // Store an error for an empty post.
        $error = "Post cannot be empty.";
    } else {
        // Prepare the update query with an ownership condition.
        $update = $pdo->prepare("UPDATE posts SET content = ? WHERE id = ? AND user_id = ?");
        // Update only the user's own post.
        $update->execute([$content, $postId, $_SESSION["user_id"]]);
        // Return to the feed after editing.
        header("Location: index.php");
        // Stop execution after redirecting.
        exit;
    }
}
// Include the shared page header.
require_once "includes/header.php";
?>
<!-- Display the edit-post card. -->
<section class="card">
    <!-- Display the edit heading. -->
    <h1>Edit Post</h1>
    <?php
    // Display a validation error when present.
    if ($error !== "") {
    ?>
        <!-- Show the edit error. -->
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php
    // End the error display.
    }
    ?>
    <!-- Start the post editing form. -->
    <form method="POST" action="edit_post.php?id=<?php echo $postId; ?>">
        <!-- Create the editable post area. -->
        <textarea name="content" maxlength="2000" required><?php echo htmlspecialchars($post["content"]); ?></textarea>
        <!-- Create the save button. -->
        <button class="button" type="submit">Save Changes</button>
        <!-- Provide a cancel link. -->
        <a class="button secondary" href="index.php">Cancel</a>
    </form>
</section>
<?php
// Include the shared page footer.
require_once "includes/footer.php";
// End the PHP section.
?>
