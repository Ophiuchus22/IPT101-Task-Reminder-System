<?php
// File name: logout.php
// Purpose: This PHP script handles user logout by destroying the session and redirecting to the login page.
// Author name: Michael Darunday, Ebszar Lapaz, and Loyd Oliver Pino
// Date of creation or last modification: 

session_start();
session_destroy();
header("Location: login.php"); // Change this to the appropriate login page
exit();
?>
