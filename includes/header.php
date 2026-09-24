<?php
// Start the shared page header.
?>
<!-- Define the HTML document type. -->
<!DOCTYPE html>
<!-- Start the HTML document and specify English as the page language. -->
<html lang="en">
<!-- Start the document head. -->
<head>
    <!-- Set the character encoding. -->
    <meta charset="UTF-8">
    <!-- Make the page responsive on phones and computers. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Display the application title in the browser tab. -->
    <title>Mini Social Media</title>
    <!-- Load the application's CSS file. -->
    <link rel="stylesheet" href="css/style.css">
</head>
<!-- Start the visible page body. -->
<body>
<!-- Create the main navigation bar. -->
<nav class="navbar">
    <!-- Display the application name. -->
    <a class="brand" href="index.php">Mini Social</a>
    <!-- Create the navigation links area. -->
    <div class="nav-links">
        <?php
        // Check whether a user is logged in.
        if (isset($_SESSION["user_id"])) {
        ?>
            <!-- Show the feed link to logged-in users. -->
            <a href="index.php">Home</a>
            <!-- Show the logout link to logged-in users. -->
            <a href="logout.php">Logout</a>
        <?php
        // Handle visitors who are not logged in.
        } else {
        ?>
            <!-- Show the login link to visitors. -->
            <a href="login.php">Login</a>
            <!-- Show the registration link to visitors. -->
            <a href="register.php">Register</a>
        <?php
        // End the authentication check.
        }
        ?>
    </div>
</nav>
<!-- Start the main content container. -->
<main class="container">
