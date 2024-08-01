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

// loginUser($conn);
// if(isLoggedIn() == true) {
//     header('location:' . BASE_URL . '/');
// }

?>

<html>

<head>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo time(); ?>">

  <!-- Page Title -->
  <title>Login | Asset360</title>

</head>

<body class="login-body">
    <div class="main" style="">
        <p class="sign"><img src="<?php echo BASE_URL . "/assets/images/logo-white.png"; ?>" class="login_logo" alt="Logo"></p>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="assets/js/loginpage.js"></script>
     
</body>

</html>