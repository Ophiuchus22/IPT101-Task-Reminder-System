<?php

// 
// File name: retrieve_reminder.php
// Purpose: This PHP file handles the server-side logic for retrieving all existing reminders from the database.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: 
// Brief overview: This script queries the database for all reminders and returns them in JSON format.
//

// Enable error reporting to help with debugging during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include 'db.php';

// Prepare the SQL statement for fetching all reminders
$sql = "SELECT * FROM reminders";
$result = $conn->query($sql);

// Create an array to hold the reminders
$reminders = array();

if ($result->num_rows > 0) {
    // Fetch all reminders and add them to the array
    while ($row = $result->fetch_assoc()) {
        $reminders[] = $row;
    }
}

// Send the reminders back to the client in JSON format
echo json_encode($reminders);

// Clean up by closing the database connection
$conn->close();
?>
