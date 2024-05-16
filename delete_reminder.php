<?php

 
// File name: delete_reminder.php
// Purpose: This PHP file handles the server-side logic for deleting a reminder from the database.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: 
// Brief overview: This script receives JSON data from an HTTP request, decodes it, deletes the reminder data from the database, 
// and returns a success or error response in JSON format.

include 'db.php'; // Include the database connection file

// Get the JSON data from the HTTP request and decode it into a PHP object
$data = json_decode(file_get_contents("php://input"));

// Extract the title, description, date, and time from the decoded data
$title = $data->title;
$description = $data->description;
$date = $data->date;
$time = $data->time;

// Prepare the SQL DELETE statement with placeholders for the parameters
$sql = "DELETE FROM reminders WHERE title = ? AND description = ? AND date = ? AND time = ? LIMIT 1";
$stmt = $conn->prepare($sql);

// Bind the parameters to the SQL statement
$stmt->bind_param("ssss", $title, $description, $date, $time);

// Execute the prepared statement
if ($stmt->execute()) {
    // If the execution is successful, return a JSON response with success set to true
    echo json_encode(array("success" => true));
} else {
    // If the execution fails, return a JSON response with success set to false and include the error message
    echo json_encode(array("success" => false, "error" => $stmt->error));
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
?>
