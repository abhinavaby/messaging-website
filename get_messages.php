<?php
// ============================================================
// get_messages.php
// Returns all messages (or only new ones) as JSON
// Frontend JS calls this every 1.5 seconds for real-time feel
// ============================================================

header("Content-Type: application/json");
include 'db.php';

// Optional: get only messages after a certain ID (saves bandwidth)
// Frontend sends ?last_id=XX to only fetch newer messages
$last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

// Check if the table was truncated (reset)
$cleared = false;
if ($last_id > 0) {
    $res_max = $con->query("SELECT MAX(id) as max_id FROM messages");
    $row_max = $res_max->fetch_assoc();
    $max_id = (int)$row_max['max_id'];
    
    // If the client's last_id is greater than the current max_id in DB,
    // it likely means the table was cleared and its auto-increment reset.
    if ($max_id < $last_id) {
        $cleared = true;
    }
}

if ($last_id > 0 && !$cleared) {
    // Fetch only messages newer than last_id
    $stmt = $con->prepare(
        "SELECT id, username, msg, created_at FROM messages WHERE id > ? ORDER BY id ASC LIMIT 50"
    );
    $stmt->bind_param("i", $last_id);
} else {
    // First load — fetch last 50 messages
    $stmt = $con->prepare(
        "SELECT id, username, msg, created_at FROM messages ORDER BY id DESC LIMIT 50"
    );
}

$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        "id"         => (int)$row['id'],
        "username"   => htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'),
        "msg"        => htmlspecialchars($row['msg'],      ENT_QUOTES, 'UTF-8'),
        "created_at" => $row['created_at']
    ];
}

// If first load: reverse so oldest is at top
if ($last_id === 0) {
    $messages = array_reverse($messages);
}

echo json_encode([
    "status" => "success", 
    "messages" => $messages,
    "cleared" => $cleared
]);

$stmt->close();
$con->close();
?>