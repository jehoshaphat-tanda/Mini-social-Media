<?php
// Start the session.
session_start();
// Include the database connection.
require_once "config/database.php";
// Include the authentication protection function.
require_once "includes/auth.php";
// Require the user to be logged in.
requireLogin();
// Read the comment ID from the URL.
$commentId = (int)($_GET["id"] ?? 0);
// Prepare a query that retrieves only the logged-in user's comment.
$stmt = $pdo->prepare("SELECT id, content FROM comments WHERE id = ? AND user_id = ?");
// Execute the ownership check.
$stmt->execute([$commentId, $_SESSION["user_id"]]);
// Fetch the comment.
$comment = $stmt->fetch();
// Stop if the comment does not exist or is not owned by the user.
if (!$comment) {
    // Return to the feed when ownership validation fails.
    header("Location: index.php");
    // Stop execution after redirecting.
    exit;
}
// Create an error variable.
$error = "";
// Check whether the edit form was submitted.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Read and trim the edited comment.
    $content = trim($_POST["content"] ?? "");
    // Validate the edited comment.
    if ($content === "") {
        // Store the empty-comment error.
        $error = "Comment cannot be empty.";
    } else {
        // Prepare the update query with an ownership condition.
        $update = $pdo->prepare("UPDATE comments SET content = ? WHERE id = ? AND user_id = ?");
        // Update only the user's own comment.
        $update->execute([$content, $commentId, $_SESSION["user_id"]]);
        // Return to the feed after saving.
        header("Location: index.php");
        // Stop execution after redirecting.
        exit;
    }
}
// Include the shared page header.
require_once "includes/header.php";
?>
<!-- Display the edit-comment card. -->
<section class="card">
    <!-- Display the edit heading. -->
    <h1>Edit Comment</h1>
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
    <!-- Start the comment editing form. -->
    <form method="POST" action="edit_comment.php?id=<?php echo $commentId; ?>">
        <!-- Create the editable comment input. -->
        <input name="content" type="text" maxlength="1000" value="<?php echo htmlspecialchars($comment["content"]); ?>" required>
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
