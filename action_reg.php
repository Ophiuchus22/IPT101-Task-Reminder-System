<?php
// Include the database connection file
include "db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoload file
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

// Start session to manage user session data
session_start();

// Retrieve the user input from the form
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];

// Function to validate username format
function validateUsername($username) {
    return preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/', $username);
}

// Function to validate password format
function validatePassword($password) {
    // Check if password contains at least one letter and one digit, and is at least six characters long
    return preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $password);
}

// Function to validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Check if the email is empty or invalid
if (empty($email) || !validateEmail($email)) { 
    header("Location: registration.php?error=Invalid email");
    exit();
}

// Check if the email already exists in the database
$email_check_query = "SELECT * FROM account WHERE email='$email' LIMIT 1"; 
$result = mysqli_query($conn, $email_check_query);
$email_exists = mysqli_fetch_assoc($result);

// Redirect back to the registration form if email already exists
if ($email_exists) {
    header("Location: registration.php?error=Email already in use");
    exit();
}

// Check if the password is empty or invalid
if (empty($password) || !validatePassword($password)) {
    header("Location: registration.php?error=Password should contain a minimum of six characters, one letter, one digit, and a special symbol");
    exit();
}

// Check if the username is empty or invalid
if (empty($username) || !validateUsername($username)) {
    header("Location: registration.php?error=Username should contain both letters and numbers, but not all numbers");
    exit();
}

// Generate verification code
$verification_code = md5(uniqid(rand(), true));

// Store verification code in session
$_SESSION['verification_code'] = $verification_code;

// Store email in session
$_SESSION['email'] = $email;


// Insert user data into the database
$sql = "INSERT INTO account (username, password, email, verification_code) VALUES ('$username', '$password', '$email', '$verification_code')";

// Execute the SQL query
if(mysqli_query($conn, $sql)){

    // Get the user ID of the newly registered user
    $user_id = mysqli_insert_id($conn);

    // Insert into password_history table
    $sql_history = "INSERT INTO password_history (user_id, password, created_at) VALUES (?, ?, NOW())";
    $stmt_history = mysqli_prepare($conn, $sql_history);
    mysqli_stmt_bind_param($stmt_history, 'is', $user_id, $password);
    mysqli_stmt_execute($stmt_history);
    mysqli_stmt_close($stmt_history);
    
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // SMTP server address
        $mail->SMTPAuth = true;
        $mail->Username = 'ipt101emailsender@gmail.com';;  // SMTP username
        $mail->Password = 'ndagxzysbqmoowxp';  // SMTP password
        $mail->SMTPSecure = 'tls';          // Enable TLS encryption
        $mail->Port = 587;                  // TCP port to connect to

        // Email content
        $mail->setFrom('ipt101emailsender@gmail.com', 'Task Reminder');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = 'Please click the "verify" link to verify your email: <a href="http://localhost/IPT101-Task-Reminder-System/verified.php">Verify</a>';

        // Send email
        $mail->send();

        header("Location: sent_notice.php?message=Registration successful. Please check your email to verify your account.");
    } catch (Exception $e) {
        header("Location: registration.php?error=Failed to send verification email. Please try again later.");
    }
} else {
    echo "ERROR: Hush! Sorry $sql. " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
email:ipt101emailsender@gmail.com
password:ipt101email