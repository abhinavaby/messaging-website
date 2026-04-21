<?php
// ============================================================
// clear_messages.php
// Receives: password via POST
// Verifies the password and truncates the messages table
// ============================================================

header("Content-Type: application/json");
include 'db.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Only POST allowed"]);
    exit;
}

$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Verify the password
if ($password !== '2007') {
    echo json_encode(["status" => "error", "message" => "Incorrect password"]);
    exit;
}

// Truncate the messages table
if ($con->query("TRUNCATE TABLE messages")) {
    echo json_encode(["status" => "success", "message" => "All messages cleared"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to clear messages"]);
}

$con->close();
?>