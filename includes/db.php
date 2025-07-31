<?php
$host = 'localhost';
$dbname = 'SMS';            // Your actual database name
$username = 'root';         // Adjust if different
$password = '';             // Adjust if needed

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional but recommended
$conn->set_charset("utf8mb4");
?>
