<?php
session_start();
require_once 'facebook-oauth.php';
require_once 'db.php';

// If the captured code param exists and is valid
if (isset($_GET['code']) && !empty($_GET['code'])) {
    // Execute cURL request to retrieve the access token
    $params = [
        'client_id' => $facebook_oauth_app_id,
        'client_secret' => $facebook_oauth_app_secret,
        'redirect_uri' => $facebook_oauth_redirect_uri,
        'code' => $_GET['code']
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);

    if (isset($response['access_token'])) {
        $access_token = $response['access_token'];

        // Get user profile data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/me?fields=id,name,email,picture&access_token=' . $access_token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $user_data = curl_exec($ch);
        curl_close($ch);
        $user_data = json_decode($user_data, true);

        $email = $user_data['email'];
        $name = $user_data['name'];
        $facebook_id = $user_data['id'];
        $profile_picture = $user_data['picture']['data']['url'];

        // Check if the user already exists in the database00
        $check_user_query = "SELECT * FROM account WHERE email = '$email'";
        $check_user_result = mysqli_query($conn, $check_user_query);

        if (mysqli_num_rows($check_user_result) > 0) {
            // User exists, log them in
            $user_row = mysqli_fetch_assoc($check_user_result);
            $_SESSION['user_id'] = $user_row['user_id'];
            $_SESSION['username'] = $user_row['username'];

            header("Location: reminder.php"); // Redirect to the remnider page after login
            exit;
        } else {
            // User doesn't exist, create a new account
            $username = $name;
            $password = '';
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_query = "INSERT INTO account (username, password, email, verified) 
                            VALUES ('$username', '$hashed_password', '$email', 1)";
            if (mysqli_query($conn, $insert_query)) {
                $user_id = mysqli_insert_id($conn);
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;

                header("Location: reminder.php"); // Redirect to the remnider page after registration
                exit;
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    } else {
        // Error handling
        echo 'Error: ' . $response['error']['message'];
    }
} else {
    // Define params and redirect to Facebook OAuth page
    $params = [
        'client_id' => $facebook_oauth_app_id,
        'redirect_uri' => $facebook_oauth_redirect_uri,
        'response_type' => 'code',
        'scope' => 'email'
    ];
    header('Location: https://www.facebook.com/' . $facebook_oauth_version . '/dialog/oauth?' . http_build_query($params));
    exit;
}
?>