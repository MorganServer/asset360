<?php
date_default_timezone_set('America/Denver');
require_once "app/database/connection.php";
require_once "path.php";
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$files = glob("app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo time(); ?>">

    <title>Login | Asset360</title>
</head>
<body class="login-body">

<div class="main" style="height: 450px;">
        <p class="sign">Sign in</p>
        <p class="sub_sign">Work Management System</p>
        <?php
            if (isset($_SESSION['error'])) {
                foreach ($_SESSION['error'] as $error) {
                    echo '<div class="alert alert-danger error-msg" role="alert">' . $error . '</div>';
                }
                // Clear the error messages after displaying them
                unset($_SESSION['error']);
            }
        ?>
        <form class="form1" method="POST">
            <input class="un " type="text" placeholder="Username" name="uname">
            <input class="pass" type="password" style="align: center;" placeholder="Password" name="password">
            <input type="submit" name="login-btn" value="Login" class="submit">
            <p class="forgot"><a href="#">Forgot Password?</a></p>
            <p class="signup">Dont have an account? <a href="#">Sign up</a></p>          
    </div>


    <!-- Bootstrap Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- end Bootstrap Scripts -->

    <script src="assets/js/loginpage.js"></script>
</body>
</html>