<?php
session_start();
require_once 'config.php';
require_once 'db.php';

// Check if the user has been redirected from Google
if (isset($_GET['code'])) {
    $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
    $gClient->setAccessToken($token['access_token']);
    // Store the access token in the session
    $_SESSION['access_token'] = $token['access_token'];
    
    // Get user profile data
    $google_oauth = new Google_Service_Oauth2($gClient);
    $google_account_info = $google_oauth->userinfo_v2_me->get();
    $email = $google_account_info->email;
    $first_name = $google_account_info->givenName;
    $last_name = $google_account_info->familyName;
    $google_id = $google_account_info->id;
    $profile_picture = $google_account_info->picture;

    // Check if the user already exists in the database00
    $check_user_query = "SELECT * FROM account WHERE email = '$email'";
    $check_user_result = mysqli_query($conn, $check_user_query);

    if (mysqli_num_rows($check_user_result) > 0) {
        // User exists, log them in
        $user_row = mysqli_fetch_assoc($check_user_result);
        $_SESSION['user_id'] = $user_row['user_id'];
        $_SESSION['username'] = $user_row['username'];
    
        header("Location: reminder.php"); // Redirect to the desired page after login
        exit;
    } else {
        // User doesn't exist, create a new account
        $username = $first_name . $last_name;
        $password = '';
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_query = "INSERT INTO account (username, password, email, verified) 
                            VALUES ('$username', '$hashed_password', '$email', 1)";
            if (mysqli_query($conn, $insert_query)) {
                $user_id = mysqli_insert_id($conn);
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;

            header("Location: reminder.php"); // Redirect to the desired page after registration
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>