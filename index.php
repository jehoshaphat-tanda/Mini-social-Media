<?php
// Start the session.
session_start();
// Include the database connection.
require_once "config/database.php";
// Include the authentication protection function.
require_once "includes/auth.php";
// Require the visitor to be logged in.
requireLogin();
// Prepare a query that retrieves posts and their authors.
$postStmt = $pdo->query("SELECT posts.id, posts.user_id, posts.content, posts.created_at, posts.updated_at, users.username FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
// Fetch all posts.
$posts = $postStmt->fetchAll();
// Include the shared navigation header.
require_once "includes/header.php";
?>
<!-- Display the welcome heading. -->
<section class="welcome">
    <!-- Show the logged-in user's username safely. -->
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
    <!-- Explain the purpose of the page. -->
    <p>Create posts, read posts, and join conversations.</p>
</section>
<!-- Display the new-post form. -->
<section class="card">
    <!-- Display the post creation heading. -->
    <h2>Create a Post</h2>
    <!-- Submit new posts to create_post.php. -->
    <form method="POST" action="create_post.php">
        <!-- Create the text area for a post. -->
        <textarea name="content" maxlength="2000" placeholder="What's on your mind?" required></textarea>
        <!-- Create the post button. -->
        <button class="button" type="submit">Post</button>
    </form>
</section>
<!-- Start the feed. -->
<section class="feed">
    <?php
    // Check whether any posts exist.
    if (!$posts) {
    ?>
        <!-- Display an empty-feed message. -->
        <div class="card"><p>No posts yet. Create the first post.</p></div>
    <?php
    // Handle the case where posts exist.
    } else {
        // Loop through every post.
        foreach ($posts as $post) {
            // Prepare a query that retrieves comments for this post.
            $commentStmt = $pdo->prepare("SELECT comments.id, comments.user_id, comments.content, comments.created_at, comments.updated_at, users.username FROM comments INNER JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.created_at ASC");
            // Execute the comment query for the current post.
            $commentStmt->execute([$post["id"]]);
            // Fetch the comments for the current post.
            $comments = $commentStmt->fetchAll();
    ?>
        <!-- Display one post card. -->
        <article class="card post">
            <!-- Display the post author's username. -->
            <h3><?php echo htmlspecialchars($post["username"]); ?></h3>
            <!-- Display the post creation time. -->
            <small><?php echo htmlspecialchars($post["created_at"]); ?></small>
            <!-- Display the post content safely while preserving line breaks. -->
            <p class="post-content"><?php echo nl2br(htmlspecialchars($post["content"])); ?></p>
            <?php
            // Check whether the logged-in user owns this post.
            if ((int)$post["user_id"] === (int)$_SESSION["user_id"]) {
            ?>
                <!-- Display owner-only post actions. -->
                <div class="actions">
                    <!-- Link to the post editing page. -->
                    <a class="button small" href="edit_post.php?id=<?php echo (int)$post["id"]; ?>">Edit</a>
                    <!-- Submit a request to delete the post. -->
                    <form class="inline-form" method="POST" action="delete_post.php" onsubmit="return confirmDelete('post');">
                        <!-- Send the post ID to the deletion script. -->
                        <input type="hidden" name="post_id" value="<?php echo (int)$post["id"]; ?>">
                        <!-- Create the delete button. -->
                        <button class="button danger small" type="submit">Delete</button>
                    </form>
                </div>
            <?php
            // End the post ownership check.
            }
            ?>
            <!-- Start the comments area. -->
            <div class="comments">
                <!-- Display the comments heading. -->
                <h4>Comments</h4>
                <?php
                // Check whether the post has comments.
                if (!$comments) {
                ?>
                    <!-- Display an empty-comments message. -->
                    <p class="muted">No comments yet.</p>
                <?php
                // Handle posts that have comments.
                } else {
                    // Loop through each comment.
                    foreach ($comments as $comment) {
                ?>
                    <!-- Display one comment. -->
                    <div class="comment">
                        <!-- Display the comment author and content. -->
                        <strong><?php echo htmlspecialchars($comment["username"]); ?>:</strong>
                        <span><?php echo nl2br(htmlspecialchars($comment["content"])); ?></span>
                        <!-- Display the comment creation time. -->
                        <small><?php echo htmlspecialchars($comment["created_at"]); ?></small>
                        <?php
                        // Check whether the logged-in user owns this comment.
                        if ((int)$comment["user_id"] === (int)$_SESSION["user_id"]) {
                        ?>
                            <!-- Display owner-only comment actions. -->
                            <div class="actions">
                                <!-- Link to the comment editing page with both IDs. -->
                                <a class="button small" href="edit_comment.php?id=<?php echo (int)$comment["id"]; ?>">Edit</a>
                                <!-- Submit a request to delete the comment. -->
                                <form class="inline-form" method="POST" action="delete_comment.php" onsubmit="return confirmDelete('comment');">
                                    <!-- Send the comment ID to the deletion script. -->
                                    <input type="hidden" name="comment_id" value="<?php echo (int)$comment["id"]; ?>">
                                    <!-- Create the comment delete button. -->
                                    <button class="button danger small" type="submit">Delete</button>
                                </form>
                            </div>
                        <?php
                        // End the comment ownership check.
                        }
                        ?>
                    </div>
                <?php
                    // End the comment loop.
                    }
                // End the comments existence check.
                }
                ?>
                <!-- Start the comment creation form. -->
                <form class="comment-form" method="POST" action="add_comment.php">
                    <!-- Send the current post ID to the comment script. -->
                    <input type="hidden" name="post_id" value="<?php echo (int)$post["id"]; ?>">
                    <!-- Create the comment input. -->
                    <input name="content" type="text" maxlength="1000" placeholder="Write a comment..." required>
                    <!-- Create the comment button. -->
                    <button class="button small" type="submit">Comment</button>
                </form>
            </div>
        </article>
    <?php
        // End the post loop.
        }
    // End the posts existence check.
    }
    ?>
</section>
<?php
// Include the shared page footer.
require_once "includes/footer.php";
// End the PHP section.


?>

