<?php
// Include the database connection file
include "db.php";

// Start session (needed for accessing session variables)
session_start();

// Get Logged-In User Information
$user_id = $_SESSION['user_id'];

// SQL Query: Select user data from the 'account' table based on the session user_id
$sql_user = "SELECT password FROM account WHERE user_id = ?";
$stmt_user = mysqli_prepare($conn, $sql_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);

if ($result_user && mysqli_num_rows($result_user) > 0) {
    $row_user = mysqli_fetch_assoc($result_user);
    $current_password_db = $row_user['password'];

    // Get User Inputs from Form
    $current_password = $_POST['currentPassword'];
    $new_password = $_POST['newPassword'];
    $confirm_password = $_POST['confirmPassword'];

    // Input Validation
    $errors = array(); 

    if ($current_password !== $current_password_db) {
        $errors[] = "Current password is incorrect.";
    }

    if ($new_password !== $confirm_password) {
        $errors[] = "New password and confirm password do not match.";
    }

    // Check if new password is different from the old one:
    if ($new_password === $current_password) {
        $errors[] = "New password cannot be the same as your current password.";
    }

    // Check if new password exists in password history
    $sql_history_check = "SELECT * FROM password_history WHERE user_id = ? AND password = ?";
    $stmt_history_check = mysqli_prepare($conn, $sql_history_check);
    mysqli_stmt_bind_param($stmt_history_check, "is", $user_id, $new_password);
    mysqli_stmt_execute($stmt_history_check);
    $result_history_check = mysqli_stmt_get_result($stmt_history_check);

    if (mysqli_num_rows($result_history_check) > 0) {
        $errors[] = "New password has been used before. Please choose a different password.";
    }

    // Validate password strength: at least one letter and one number
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/', $new_password)) { 
        $errors[] = "New password must contain at least one letter and one number.";
    }

    // Handle Validation Errors
    if (!empty($errors)) {
        $_SESSION['user_pass_error'] = implode(", ", $errors); 
        header("Location: reminder.php");
        exit();
    }

    // Update User Password in the Database
    $sql = "UPDATE account SET password = ? WHERE user_id = ?";
    $stmt_update = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt_update, 'si', $new_password, $user_id); 

    if (mysqli_stmt_execute($stmt_update)) {
        // Insert into password_history
        $sql_history = "INSERT INTO password_history (user_id, password, created_at) VALUES (?, ?, NOW())"; 
        $stmt_history = mysqli_prepare($conn, $sql_history);
        mysqli_stmt_bind_param($stmt_history, 'is', $user_id, $new_password); // Store plain text password
        mysqli_stmt_execute($stmt_history);

        $_SESSION['user_pass_success'] = "Your password has been updated successfully";
        header("Location: reminder.php");
    } else {
        $error_message = mysqli_error($conn);
        $_SESSION['user_pass_error'] = "Your password could not be updated: $error_message";
        header("Location: reminder.php");
        exit();
    }
}
?>
