// File name: store_reminder.php
// Purpose: This PHP file handles the server-side logic for adding a new reminder to the database.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: May 17, 2024
// Brief overview: This script receives JSON data from an HTTP request, decodes it, inserts the reminder data into the database, 
// and returns a success or error response in JSON format.
//

<?php
include 'db.php';
// Decode the JSON data received from the HTTP request.
$data = json_decode(file_get_contents("php://input"), true);
// Check if the JSON data is valid and contains all necessary fields.
if (!$data || !isset( $data['title'], $data['description'], $data['date'], $data['time'])) {
    echo json_encode(array("success" => false, "error" => "Invalid JSON data"));
    exit;
}
// Extract the reminder data from the decoded JSON.
$title = $data['title'];
$description = $data['description'];
$date = $data['date'];
$time = $data['time'];
// Prepare an SQL statement to update the reminders table.
$sql = "UPDATE reminders SET title = ?, description = ?, date = ?, time = ?";
$stmt = $conn->prepare($sql);
// Bind the reminder data to the SQL statement parameters.
$stmt->bind_param("ssss", $title, $description, $date, $time);

// Execute the SQL statement.
if ($stmt->execute()) {
       // Send a JSON response indicating success.
    echo json_encode(array("success" => true));
} else {
    // Send a JSON response indicating failure and include the error message.
    echo json_encode(array("success" => false, "error" => $stmt->error));
}

// Close the prepared statement and the database connection.
$stmt->close();
$conn->close();
?>
