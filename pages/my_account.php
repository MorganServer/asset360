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
                                        $fname                  = $a_row['fname'];
                                        $lname                  = $a_row['lname'];
                                        $email                  = $a_row['email'];
                                        $account_type           = $a_row['account_type'];
                                        $account_created        = $a_row['account_created'];

                                        // Format maintenance schedule if not null
                                        $f_account_created = !empty($account_created) ? date_format(date_create($account_created), 'M d, Y') : '-';
                                    
                                    }}}
                        ?>
                    <div class="card w-100" style="border-top: 4px solid rgb(0, 43, 73); border-radius: 3px !important;">
                      <div class="card-body p-4">
                        <h5 class="card-title">Account Information</h5>
                        <p class="card-text">
                        <div class="text-secondary d-flex justify-content-center align-items-center mx-auto" style="border-radius: 100%; border: 4px solid #6c757d; width: 150px; height: 150px; overflow: hidden;">
                            <img src="../assets/images/bg-profile-pic.JPG" style="width: 93%; height: 93%; border-radius: 100%;" alt="">
                            
                        </div>
                        <div class="text-center mt-3">
                            <p class="text-secondary text-capitalize">
                                <?php echo $account_type; ?> Account
                            </p>
                            <p class="text-secondary" style="margin-top: -10px;">
                                Member since <?php echo $f_account_created; ?>
                            </p>
                        </div>
                        <br>
                        

                        <form>
                          <div class="row mb-3">
                            <label for="fname" class="col-sm-2 col-form-label">First Name</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $fname; ?>">
                            </div>
                          </div>
                          <div class="row mb-3">
                            <label for="lname" class="col-sm-2 col-form-label">Last Name</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $lname; ?>">
                            </div>
                          </div>
                          <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                            </div>
                          </div>
                          <!-- <div class="row mb-3">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                              <input type="password" class="form-control" id="password" name="password">
                            </div>
                          </div> -->
                    
                          
                          <button type="submit" name="update" class="btn btn-primary">Update</button>
                        </form>

                        </p>
                      </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card w-100" style="border-top: 4px solid rgb(0, 43, 73); border-radius: 3px !important;">
                      <div class="card-body ps-2">
                        <h5 class="card-title">Admin Settings</h5>

                        <div class="row ms-3">
                            <div class="card pe-2" style="width: 10rem;">
                              <img src="..." class="card-img-top" alt="...">
                              <div class="card-body">
                                <p class="card-text text-center" style="font-size: 35px;"><i class="bi bi-shield-lock-fill"></i></p>
                                <h5 class="card-title text-center">Security</h5>
                                <h6 class="card-subtitle mb-2 text-body-secondary">Change Password, Two-factor</h6>
                              </div>
                            </div>
                            <div class="card pe-2" style="width: 10rem;">
                              <img src="..." class="card-img-top" alt="...">
                              <div class="card-body">
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <h5 class="card-title text-center">Card title</h5>
                              </div>
                            </div>
                            <div class="card" style="width: 10rem;">
                              <img src="..." class="card-img-top" alt="...">
                              <div class="card-body">
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <h5 class="card-title text-center">Card title</h5>
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            
        </div>
    <!-- END main-container -->

</body>
</html>