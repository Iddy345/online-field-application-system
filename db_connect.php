<?php
// db_connect.php

// Database configuration
$host = 'localhost'; // Or your database host, e.g., '127.0.0.1'
$db   = 'your_database_name'; // Replace with your actual database name
$user = 'your_database_user'; // Replace with your actual database username
$pass = 'your_database_password'; // Replace with your actual database password
$charset = 'utf8mb4'; // Character set for database communication

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch rows as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Disable emulation for real prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Connected to the database successfully!"; // For testing connection
} catch (\PDOException $e) {
    // Log the error message securely (don't display to users in production)
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}
?>