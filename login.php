<?php
// Start the session.
session_start();
// Include the database connection.
require_once "config/database.php";
// Redirect already logged-in users to the feed.
if (isset($_SESSION["user_id"])) {
    // Send the logged-in user to the home page.
    header("Location: index.php");
    // Stop execution after the redirect.
    exit;
}
// Create a variable for login errors.
$error = "";
// Check whether the login form was submitted.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Read and trim the submitted email.
    $email = trim($_POST["email"] ?? "");
    // Read the submitted password.
    $password = $_POST["password"] ?? "";
    // Validate that both login fields were provided.
    if ($email === "" || $password === "") {
        // Store the validation error.
        $error = "Email and password are required.";
    } else {
        // Prepare a query that finds the account by email.
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
        // Execute the account lookup.
        $stmt->execute([$email]);
        // Fetch the matching account.
        $user = $stmt->fetch();
        // Check the submitted password against the stored hash.
        if ($user && password_verify($password, $user["password"])) {
            // Regenerate the session ID to reduce session fixation risk.
            session_regenerate_id(true);
            // Store the logged-in user's ID in the session.
            $_SESSION["user_id"] = $user["id"];
            // Store the logged-in user's username in the session.
            $_SESSION["username"] = $user["username"];
            // Redirect to the social feed.
            header("Location: index.php");
            // Stop execution after the redirect.
            exit;
        } else {
            // Store a generic login error.
            $error = "Invalid email or password.";
        }
    }
}
// Include the shared navigation header.
require_once "includes/header.php";
?>
<!-- Display the login card. -->
<section class="auth-card">
    <!-- Display the login heading. -->
    <h1>Login</h1>
    <?php
    // Display a successful-registration message.
    if (isset($_GET["registered"])) {
    ?>
        <!-- Show the registration success message. -->
        <div class="alert success">Registration successful. Please log in.</div>
    <?php
    // End the registration message.
    }
    // Display an error message when login fails.
    if ($error !== "") {
    ?>
        <!-- Show the login error. -->
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php
    // End the error display.
    }
    ?>
    <!-- Start the login form. -->
    <form method="POST" action="login.php">
        <!-- Display the email label. -->
        <label for="email">Email</label>
        <!-- Create the email input. -->
        <input id="email" name="email" type="email" required>
        <!-- Display the password label. -->
        <label for="password">Password</label>
        <!-- Create the password input. -->
        <input id="password" name="password" type="password" required>
        <!-- Create the login button. -->
        <button class="button" type="submit">Login</button>
    </form>
    <!-- Provide a link to registration. -->
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</section>
<?php
// Include the shared page footer.
require_once "includes/footer.php";
// End the PHP section.
?>
