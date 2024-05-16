<?php

// 
// File name: db.php
// Purpose: This PHP script establishes a connection to the database.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: 
// Brief overview: This script sets up the connection parameters for the database and -
// establishes a connection, providing feedback on success or failure.
//

//Database connection parameters
$sname = "localhost:3307"; // Server name and port
$uname = "root"; // Database username
$password = ""; // Database password
$db_name = "reminder"; // Database name

//To establish a connection to the database
$conn = new mysqli($sname, $uname, $password, $db_name);

//To heck if the connection is successful
if (!$conn) {
    echo "Failed to connect to the database"; //To display message if connection fails
} else {
    echo "Connection success"; //To display message if connection is successful
}
?>
