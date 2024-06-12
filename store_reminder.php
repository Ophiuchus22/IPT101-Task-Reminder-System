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
header('Content-Type: application/json');


// Include the database connection file
include 'db.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare data received from the client
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $status = "pending";


    // SQL query to insert data into reminders table
    $sql = "INSERT INTO reminders (title, description, date, time, status)
            VALUES ('$title', '$description', '$date', '$time','$status')";

    // Check if the connection is still open
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Perform the query
    if ($conn->query($sql) === TRUE) {
        // Insertion successful
        $response = array("success" => true);
    } else {
        // Insertion failed
        $response = array("success" => false, "error" => "Error: " . $sql . "<br>" . $conn->error);
    }

    // Send JSON response back to the client
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(array("error" => "Method Not Allowed"));
}

// Close connection
$conn->close();
?>
