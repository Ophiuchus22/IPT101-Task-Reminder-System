<?php
include "db.php"; // Include database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload file
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Check if required data is received via POST
if (isset($_POST['email']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['date']) && isset($_POST['time'])) {
    $email = $_POST['email'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // SMTP server address
        $mail->SMTPAuth = true;
        $mail->Username = 'ipt101emailsender@gmail.com'; // SMTP username
        $mail->Password = 'ndagxzysbqmoowxp';  // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

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
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>
