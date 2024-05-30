<?php
require_once'config.php';  //include the config file
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .login-dark form {
        max-width:320px;
        width:90%;
        background-color:#1e2833;
        padding:40px;
        border-radius:4px;
        transform:translate(-50%, -50%);
        position:absolute;
        top:50%;
        left:50%;
        color:#fff;
        box-shadow:3px 3px 4px rgba(0,0,0,0.2);
        }

        .login-dark .illustration {
        text-align:center;
        padding:15px 0 20px;
        font-size:100px;
        color:#2980ef;
        }

        .login-dark form .form-control {
        background:none;
        border:none;
        border-bottom:1px solid #434a52;
        border-radius:0;
        box-shadow:none;
        outline:none;
        color:inherit;
        }

        .login-dark form .btn-primary {
        background:#214a80;
        border:none;
        border-radius:4px;
        padding:11px;
        box-shadow:none;
        margin-top:26px;
        text-shadow:none;
        outline:none;
        }

        .login-dark form .btn-primary:hover, .login-dark form .btn-primary:active {
        background:#214a80;
        outline:none;
        }

        .login-dark form .register-link {
        display: block;
        text-align: center;
        font-size: 14px;
        color: #6f7a85; /* Light gray */
        opacity: 0.9;
        text-decoration: none;
        margin-top: 10px; /* Space above the link */
        }

        .login-dark form .register-link:hover {
        opacity: 1;
        text-decoration: underline; /* Underline on hover */
        }

        .login-dark form .register-link span {
        color: #2980ef; /* Blue color for "Register" */
        }

        .social-icons {
        display: flex;
        justify-content: center;
        margin-top: 15px; /* Add some space above the icons */
        }

        .social-icons a {
        margin: 0 10px; /* Space between the icons */
        }

        .social-icons a i {  /* Target the <i> element within the links */
        font-size: 24px; /* Adjust the size as needed */
        }

        .login-dark form .or-separator {
        text-align: center;
        margin-top: 10px; /* Adjust spacing as needed */
        color: #6f7a85; /* Light gray to match other text */
        font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="login-dark">
        <form method="post" action="action_login.php">
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_GET['error']; ?>
            </div>
        <?php } ?>
            <h1 class="text-center mb-4">Login</h1>
            <div class="form-group"><input class="form-control" type="username" name="username" placeholder="Username" required></div>
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Log In</button></div>
            <a href="registration.php" class="register-link">Don't have an account? <span>Register</span></a>
            <div class="or-separator">or</div>
            <div class="social-icons">
                <a href="#" onclick="window.location = '<?php echo $login_url; ?>'"> 
                    <i class="fab fa-google"></i>
                </a>
            </div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>