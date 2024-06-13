<?php
header('Content-Type: application/json');

// Start the session
session_start();

// Include the database connection file
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, respond with an error or redirect as needed
    http_response_code(401); // Unauthorized
    echo json_encode(array("error" => "User not logged in"));
    exit();
}

$user_id = $_SESSION['user_id'];

// Update reminders that are in the past to 'completed' for this user
$update_sql = "UPDATE reminders SET status = 'Completed' WHERE user_id = ? AND CONCAT(date, ' ', time) < NOW()";
$stmt_update = $conn->prepare($update_sql);
$stmt_update->bind_param("i", $user_id);
$stmt_update->execute();
$stmt_update->close();

// Fetch reminders for this user
$sql = "SELECT id, title, description, date, time, status FROM reminders WHERE user_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$reminders = array();
while ($row = $result->fetch_assoc()) {
    $reminders[] = $row;
}

echo json_encode($reminders);

$stmt->close();
$conn->close();
?>
