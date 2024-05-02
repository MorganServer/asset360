<?php
date_default_timezone_set('America/Denver');
require_once "../app/database/connection.php";
// require_once "app/functions/add_app.php";
require_once "../path.php";
session_start();

$files = glob("../app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}
logoutUser($conn);
if(isLoggedIn() == false) {
    header('location:' . BASE_URL . '/login.php');
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
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo time(); ?>">

    <title>My Account | Asset360</title>
</head>
<body>
    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

    <!-- main-container -->
        <div class="container" style="padding: 0 75px 0 75px;">
            <h2 class="mt-4">
                My Account
            </h2>
            <hr>

            <div class="row">
                <div class="col">
                    <div class="card w-100" style="border-top: 4px solid rgb(0, 43, 73); border-radius: 3px !important;">
                      <div class="card-body ps-2">
                        <h5 class="card-title">Account Information</h5>
                        <p class="card-text">
                            <div class="text-secondary d-flex mx-auto" style="border-radius: 100px; border: 2.5px solid #6c757d; width: 100px; height: 100px">
                                <img src="../assets/images/profile-pic.png" style="width: 100px; height: 100px;" alt="">
                            </div>

                        </p>
                      </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card w-100" style="border-top: 4px solid rgb(0, 43, 73); border-radius: 3px !important;">
                      <div class="card-body ps-2">
                        <h5 class="card-title">Account Statistics</h5>
                        <p class="card-text">

                        </p>
                      </div>
                    </div>
                </div>
            </div>
            
        </div>
    <!-- END main-container -->

</body>
</html>