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
                        <?php
                            $user_id = $_SESSION['user_id'];

                            $a_sql = "SELECT * FROM users WHERE user_id = $user_id";
                            $a_result = mysqli_query($conn, $a_sql);
                            if($a_result) {
                                $a_num_rows = mysqli_num_rows($a_result);
                                if($a_num_rows > 0) {
                                    while ($a_row = mysqli_fetch_assoc($a_result)) {
                                        $id                     = $a_row['user_id'];
                                        $idno                   = $a_row['idno'];
                                        $fname                  = $a_row['fname'];
                                        $email                  = $a_row['email'];
                                        $account_type           = $a_row['account_type'];
                                        $account_created        = $a_row['account_created'];

                                        // Format maintenance schedule if not null
                                        $f_account_created = !empty($account_created) ? date_format(date_create($account_created), 'M d, Y') : '-';
                                    
                                    }}}
                        ?>
                    <div class="card w-100" style="border-top: 4px solid rgb(0, 43, 73); border-radius: 3px !important;">
                      <div class="card-body ps-2">
                        <h5 class="card-title">Account Information</h5>
                        <p class="card-text">
                        <div class="text-secondary d-flex justify-content-center align-items-center mx-auto" style="border-radius: 100%; border: 4px solid #6c757d; width: 150px; height: 150px; overflow: hidden;">
                            <img src="../assets/images/bg-profile-pic.JPG" style="width: 93%; height: 93%; border-radius: 100%;" alt="">
                            
                        </div>
                        <div class="text-center">
                            <p class="text-secondary">
                                <?php echo $account_type; ?>
                            </p>
                            <p class="text-secondary">
                                Member since: <?php echo $f_account_created; ?>
                            </p>
                        </div>
                        

                        <form>
                          <div class="row mb-3">
                            <label for="fname" class="col-sm-2 col-form-label">First Name</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="fname" value="<?php echo $fname; ?>">
                            </div>
                          </div>
                          <div class="row mb-3">
                            <label for="lname" class="col-sm-2 col-form-label">Last Name</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="lname" value="<?php echo $lname; ?>">
                            </div>
                          </div>
                          <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="email" value="<?php echo $email; ?>">
                            </div>
                          </div>
                          <div class="row mb-3">
                            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                              <input type="password" class="form-control" id="inputPassword3">
                            </div>
                          </div>
                          <fieldset class="row mb-3">
                            <legend class="col-form-label col-sm-2 pt-0">Radios</legend>
                            <div class="col-sm-10">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" checked>
                                <label class="form-check-label" for="gridRadios1">
                                  First radio
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
                                <label class="form-check-label" for="gridRadios2">
                                  Second radio
                                </label>
                              </div>
                              <div class="form-check disabled">
                                <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios3" value="option3" disabled>
                                <label class="form-check-label" for="gridRadios3">
                                  Third disabled radio
                                </label>
                              </div>
                            </div>
                          </fieldset>
                          <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck1">
                                <label class="form-check-label" for="gridCheck1">
                                  Example checkbox
                                </label>
                              </div>
                            </div>
                          </div>
                          <button type="submit" class="btn btn-primary">Sign in</button>
                        </form>



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