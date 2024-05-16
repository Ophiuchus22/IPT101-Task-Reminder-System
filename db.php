<?php
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
