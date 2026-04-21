<?php
// ============================================================
// send_message.php
// Receives: username, message via POST
// Saves it into the database and returns JSON status
// ============================================================

header("Content-Type: application/json");
include 'db.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Only POST allowed"]);
    exit;
}

// Get and sanitize inputs
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$msg      = isset($_POST['msg'])      ? trim($_POST['msg'])      : '';

// Validate — don't save empty messages or names
if (empty($username) || empty($msg)) {
    echo json_encode(["status" => "error", "message" => "Username and message required"]);
    exit;
}

// Limit name to 50 chars and message to 2000 chars
$username = substr($username, 0, 50);
$msg      = substr($msg, 0, 2000);

// Use prepared statement to prevent SQL injection
$stmt = $con->prepare("INSERT INTO messages (username, msg) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $msg);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "id" => $con->insert_id]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save message"]);
}

$stmt->close();
$con->close();
?>