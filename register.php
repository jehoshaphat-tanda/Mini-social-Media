<?php
// Start the session.
session_start();
// Include the database connection.
require_once "config/database.php";
// Create variables for possible form errors and old input.
$error = "";
// Check whether the registration form was submitted.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Read and trim the username submitted by the user.
    $username = trim($_POST["username"] ?? "");
    // Read and trim the email submitted by the user.
    $email = trim($_POST["email"] ?? "");
    // Read the password submitted by the user.
    $password = $_POST["password"] ?? "";
    // Read the password confirmation submitted by the user.
    $confirmPassword = $_POST["confirm_password"] ?? "";
    // Validate that no registration field is empty.
    if ($username === "" || $email === "" || $password === "" || $confirmPassword === "") {
        // Store the validation error.
        $error = "All fields are required.";
    // Validate the email address.
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Store the email validation error.
        $error = "Please enter a valid email address.";
    // Validate that both passwords match.
    } elseif ($password !== $confirmPassword) {
        // Store the password mismatch error.
        $error = "Passwords do not match.";
    // Validate a minimum password length.
    } elseif (strlen($password) < 6) {
        // Store the password length error.
        $error = "Password must contain at least 6 characters.";
    } else {
        // Prepare a query that checks whether the username or email already exists.
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        // Execute the duplicate-account check.
        $check->execute([$username, $email]);
        // Check whether a matching account was found.
        if ($check->fetch()) {
            // Store the duplicate-account error.
            $error = "Username or email already exists.";
        } else {
            // Convert the plain password into a secure password hash.
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Prepare the account insertion query.
            $insert = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            // Execute the account insertion query.
            $insert->execute([$username, $email, $hashedPassword]);
            // Redirect the newly registered user to the login page.
            header("Location: login.php?registered=1");
            // Stop the current script after redirecting.
            exit;
        }
    }
}
// Include the shared navigation header.
require_once "includes/header.php";
?>
<!-- Display the registration card. -->
<section class="auth-card">
    <!-- Display the registration heading. -->
    <h1>Create Account</h1>
    <?php
    // Display an error message when validation fails.
    if ($error !== "") {
    ?>
        <!-- Show the registration error. -->
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php
    // End the error display.
    }
    ?>
    <!-- Start the registration form. -->
    <form method="POST" action="register.php">
        <!-- Display the username label. -->
        <label for="username">Username</label>
        <!-- Create the username input. -->
        <input id="username" name="username" type="text" maxlength="50" required>
        <!-- Display the email label. -->
        <label for="email">Email</label>
        <!-- Create the email input. -->
        <input id="email" name="email" type="email" maxlength="100" required>
        <!-- Display the password label. -->
        <label for="password">Password</label>
        <!-- Create the password input. -->
        <input id="password" name="password" type="password" required>
        <!-- Display the confirmation label. -->
        <label for="confirm_password">Confirm Password</label>
        <!-- Create the password confirmation input. -->
        <input id="confirm_password" name="confirm_password" type="password" required>
        <!-- Create the registration button. -->
        <button class="button" type="submit">Register</button>
    </form>
    <!-- Provide a link to the login page. -->
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</section>
<?php
// Include the shared page footer.
require_once "includes/footer.php";
// End the PHP section.
?>
