<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENT NOTICE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .notice-container {
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

        .notice-container .form-control {
            background: none;
            border: none;
            border-bottom: 1px solid #434a52;
            border-radius: 0;
            box-shadow: none;
            outline: none;
            color: inherit;
        }

        .notice-container h2 {
            margin-bottom: 20px;
        }

        .btn-primary {
            min-width: 150px; /* Adjust the width as needed */
        }
    </style>
</head>

<body>

    <div class="notice-container">
        <i class="bi bi-envelope-fill display-1 text-primary mb-3"></i>
        <h2>NOTICE</h2>
        
        <?php if (isset($_GET['message'])) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_GET['message']; ?>
        </div>
        <?php } ?>

        <form action="login.php" method="post">
            <button type="submit" class="btn btn-primary btn-lg">Log In</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>

</body>

</html>
