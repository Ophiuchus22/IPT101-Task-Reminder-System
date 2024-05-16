<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db.php';

// Read and decode the JSON data sent in the request body
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(array("success" => false, "error" => "Invalid JSON data"));
    exit;
}

// Extract individual fields from the decoded JSON array
$title = $data['title'];
$description = $data['description'];
$date = $data['date'];
$time = $data['time'];

// Prepare and execute the SQL insert statement
$sql = "INSERT INTO reminders (title, description, date, time) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $title, $description, $date, $time);

$response = array();
if ($stmt->execute()) {
    $response["success"] = true;
} else {
    $response["success"] = false;
    $response["error"] = $stmt->error;
}

// Return the response as JSON
echo json_encode($response);

$stmt->close();
$conn->close();
?>
