<?php

// 
// File name: store_reminder.php
// Purpose: This PHP file handles the server-side logic for adding a new reminder to the database.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: 
// Brief overview: This script receives JSON data from an HTTP request, decodes it, inserts the reminder data into the database, 
// and returns a success or error response in JSON format.
//

// Enable error reporting to help with debugging during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include 'db.php';

// Get the raw JSON data from the request body and decode it into a PHP array
$data = json_decode(file_get_contents('php://input'), true);

// Check if the data was decoded properly
if (!$data) {
    // If the data is invalid, send back an error response and stop the script
    echo json_encode(array("success" => false, "error" => "Invalid JSON data"));
    exit;
}

// Extract the individual pieces of information from the decoded array
$title = $data['title'];
$description = $data['description'];
$date = $data['date'];
$time = $data['time'];

// Prepare the SQL statement for inserting the new reminder into the database
$sql = "INSERT INTO reminders (title, description, date, time) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
// Bind the extracted data to the SQL statement; "ssss" means all four parameters are strings
$stmt->bind_param("ssss", $title, $description, $date, $time);

// Create an array to hold the response
$response = array();

if ($stmt->execute()) {
    // If the execution is successful, set the success flag to true
    $response["success"] = true;
} else {
    // If there was an error, set the success flag to false and include the error message
    $response["success"] = false;
    $response["error"] = $stmt->error;
}

// Send the response back to the client in JSON format
echo json_encode($response);

// Clean up by closing the prepared statement and the database connection
$stmt->close();
$conn->close();
?>
