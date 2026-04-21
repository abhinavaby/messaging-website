<?php
// ============================================================
// Database Connection File
// XAMPP default settings: host=localhost, user=root, pass=""
// ============================================================

$host    = "localhost";   // XAMPP MySQL host
$user    = "root";        // Default XAMPP MySQL username
$pass    = "";            // Default XAMPP MySQL password (empty)
$db_name = "chat_app";    // The database we created in setup.sql

// Create connection
$con = new mysqli($host, $user, $pass, $db_name);

// Check connection — shows error if DB not found
if ($con->connect_error) {
    die(json_encode([
        "error" => "Database connection failed: " . $con->connect_error
    ]));
}

// Set charset to support all languages and emojis
$con->set_charset("utf8mb4");
?>