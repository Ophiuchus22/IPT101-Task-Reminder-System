<?php
// Include database connection and PHPMailer files
include 'db.php';

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Start session to access user session variables
session_start();

// Retrieve input data from JSON POST request
$input = json_decode(file_get_contents('php://input'), true);

// Check if required data is received via POST
if (isset($input['title']) && isset($input['description']) && isset($input['date']) && isset($input['time'])) {
    $title = $input['title'];
    $description = $input['description'];
    $date = $input['date'];
    $time = $input['time'];
    $user_id = $_SESSION['user_id']; // Retrieve user ID from session

    // Fetch user's email from the database
    $sql = "SELECT email FROM account WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    if ($email) {
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // SMTP server address
            $mail->SMTPAuth = true;
            $mail->Username = 'ipt101emailsender@gmail.com';  // SMTP username
            $mail->Password = 'ndagxzysbqmoowxp';  // SMTP password
            $mail->SMTPSecure = 'tls';  // Enable TLS encryption
            $mail->Port = 587;  // TCP port to connect to

            // Email content
            $mail->setFrom('ipt101emailsender@gmail.com', 'Task Reminder');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Reminder Notification';
            $mail->Body = "<h1>$title</h1><p>$description</p><p>Date: $date</p><p>Time: $time</p>";

            // Send email
            $mail->send();

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
            // Log the error for further debugging
            error_log('PHPMailer Error: ' . $mail->ErrorInfo);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'User email not found']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>
