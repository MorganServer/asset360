<?php
date_default_timezone_set('America/Denver');
require_once "../../app/database/connection.php";
// require_once "app/functions/add_app.php";
require_once "../../path.php";
session_start();

$files = glob("../../app/functions/*.php");
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
    <link rel="stylesheet" href="../../assets/css/styles.css?v=<?php echo time(); ?>">

    <title>My Account | Asset360</title>
</head>
<body>
    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

    <!-- main-container -->
        <div class="container" style="padding: 0 75px 0 75px;">
            <h2 class="mt-4">
                Change Password
            </h2>
            <hr>

            <div class="mb-4" style="background-color: rgb(229, 233, 236); border-radius: 5px; padding: 10px;">
                <p>
                    You may change your password at any time by following the steps below:
                </p>
                <ul>
                    <li>Enter your current password and new password. You will need to enter the new password twice in order to confirm that you have entered it correctly.</li>
                    <li>An email with a confirmation number will be sent to your email address on record. Enter the confirmation number on the next page.</li>
                    <li>The password change will be effective immediately.</li>
                </ul>
            </div>
            

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
                                        $uname                  = $a_row['uname'];
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
                            <label for="uname" class="col-sm-2 col-form-label">User Name</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="uname" name="uname" value="<?php echo $uname; ?>">
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
            
        </div>
    <!-- END main-container -->

</body>
</html>