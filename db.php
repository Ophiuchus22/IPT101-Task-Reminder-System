<?php
// File name: db.php
// Purpose: This PHP script establishes a connection to the database.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: 
// Brief overview: This script sets up the connection parameters for the database and -
// establishes a connection, providing feedback on success or failure.

// Database connection parameters
$sname = "localhost:3307"; // Server name and port
$uname = "root"; // Database username
$password = ""; // Database password
$db_name = "reminder"; // Database name

// Establish a connection to the database
$conn = new mysqli($sname, $uname, $password, $db_name);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Terminate script with error message
} else {
    // echo "Connection success"; // This message is okay for debugging, but in production, consider logging instead
}

// It's good practice to close the database connection when done
?>
