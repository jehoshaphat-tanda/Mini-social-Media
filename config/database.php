<?php
// Start the PHP section of this file.
$host = "localhost";
// Define the MySQL server host name.
$dbname = "social_media";
// Define the database name.
$dbuser = "root";
// Define the default XAMPP MySQL username.
$dbpass = "";
// Define the default XAMPP MySQL password.
try {
    // Create a PDO connection to the MySQL database.
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    // Tell PDO to report database errors as exceptions.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Tell PDO to return database rows as associative arrays.
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Stop the application if the database connection fails.
    die("Database connection failed: " . $e->getMessage());
}
// End the PHP section of this file.
?>
