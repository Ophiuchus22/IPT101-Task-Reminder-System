<?php
// To connect with the database connection file
include "db.php";

// Start session
session_start();

// Retrieve the email and verification code from the session
$email = $_SESSION['email'] ?? '';
$verification_code = $_SESSION['verification_code'] ?? '';

// Check if email and verification code are empty
if (empty($email) || empty($verification_code)) {
    echo "Invalid verification request.";
    exit();
}

// Check if the verification code exists in the database
$sql = "SELECT * FROM account WHERE email='$email' AND verification_code='$verification_code'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Update the 'verified' column to 1
    $sql_update = "UPDATE account SET verified = 1 WHERE email='$email' AND verification_code='$verification_code'";
    if (mysqli_query($conn, $sql_update)) {
        echo '
        <!DOCTYPE html>
        <html>

        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Verification</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

            <style>
                .verification-container {
                    max-width: 320px;
                    width: 90%;
                    background-color: #1e2833;
                    padding: 40px;
                    border-radius: 4px;
                    transform: translate(-50%, -50%);
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    color: #fff;
                    box-shadow: 3px 3px 4px rgba(0, 0, 0, 0.2);
                    text-align: center;
                }

                .verification-container h2 {
                    margin-bottom: 20px;
                }

                .btn-primary {
                    min-width: 150px; /* Adjust the width as needed */
                }
            </style>
        </head>

        <body>

            <div class="verification-container">
                <i class="bi bi-check-circle-fill display-1 text-success mb-3"></i>
                <h2>Email has been successfully verified</h2>
                <form action="login.php" method="post">
                    <button type="submit" class="btn btn-primary btn-lg">LOG IN</button>
                </form>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>

        </body>

        </html>
        
        ';
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid verification code.";
}

// Closing the database connection
mysqli_close($conn);
?>
