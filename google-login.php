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
        // To generate random default username.
        function generate_random_username() {
            $words = ["apple", "brave", "charm", "droid", "eagle", "fable", "gleam", "hatch", "icily", "jolly", "karma", "lucky", "mango", 
                      "neato", "omega", "pearl", "quark", "risky", "smile", "tango", "ultra", "vivid", "whale", "xenon", "young", "zesty",
                      "frost", "grape", "honey", "jumpy", "knock", "lemon", "mocha", "nacho", "pinky", "queen", "robot", "sunny", "tiger", 
                      "unite", "velvet", "wizard", "xylos", "yummy", "zephyr", "azure", "blitz", "coral", "dream", "ember", "flora", "glint", 
                      "hover", "ivory", "jewel", "kebab", "lyric", "mirth", "novel", "olive", "plume", "quilt", "raven", "swift", "twirl", 
                      "urban", "valor", "whirl", "xylos", "yacht", "zebra"];
            $word = $words[array_rand($words)];
            
            // Ensure the word is at most 5 letters
            if (strlen($word) > 5) {
                $word = substr($word, 0, 5);
            }
            // Generate a random number with up to 3 digits
            $number = rand(0, 999);
            // Combine word and number ensuring total length is 8 characters
            $username = $word . $number;
            // Truncate if necessary to ensure total length is 8 characters
            $username = substr($username, 0, 8);
            
            return $username;
        }
        
        // New User: Create Account
        // ------------------------
        $username = generate_random_username(); // Generate a random username.
        $password = 'user123';                  // Default password..

        $insert_query = "INSERT INTO account (username, password, email, verified) 
                            VALUES ('$username', '$password', '$email', 1)";
            if (mysqli_query($conn, $insert_query)) {
                $user_id = mysqli_insert_id($conn);
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
            
            // Insert Default Password into Password History
            $insert_password_history_query = "INSERT INTO password_history (user_id, password, created_at) VALUES ('$user_id', '$password', NOW())"; 
            if (!mysqli_query($conn, $insert_password_history_query)) {
                echo "Error: " . mysqli_error($conn); // Display an error if the password history insert fails.
            }

            header("Location: reminder.php"); // Redirect to the desired page after registration
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>