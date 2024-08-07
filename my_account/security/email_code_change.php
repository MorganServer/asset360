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
    <style>
        .wrong .bi-check {
            display: none;
        }
        .good .bi-x {
            display: none;
        }
        .valid-feedback,
        .invalid-feedback {
          margin-left: calc(2em + 0.25rem + 1.5rem);
        }
    </style>

</head>
<body>
    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

    <!-- Main container -->
    <div class="container" style="padding: 0 75px 0 75px;">
        <h2 class="mt-4">Confirmation Code</h2>
        <hr>

        <!-- Information -->
        <div class="mb-4" style="background-color: rgb(229, 233, 236); border-radius: 5px; padding: 10px;">
            <p>Check your email for the confirmation code.</p>
            <!-- <ul>
                <li>Enter your current password and new password. You will need to enter the new password twice in order to confirm that you have entered it correctly.</li>
                <li>An email with a confirmation number will be sent to your email address on record. Enter the confirmation number on the next page.</li>
                <li>The password change will be effective immediately.</li>
            </ul> -->
        </div>

        <!-- Account Information -->
        <div class="col">
            <div class="card w-100" style="border-top: 4px solid rgb(0, 43, 73); border-radius: 3px !important;">
                <div class="card-body p-4">
                <?php
                            $user_id = $_SESSION['user_id'];

                            $cp_sql = "SELECT * FROM users WHERE user_id = $user_id";
                            $cp_result = mysqli_query($conn, $cp_sql);
                            if($cp_result) {
                                $cp_num_rows = mysqli_num_rows($cp_result);
                                if($cp_num_rows > 0) {
                                    while ($cp_row = mysqli_fetch_assoc($cp_result)) {
                                        $id                     = $cp_row['user_id'];
                                        $fname                  = $cp_row['fname'];
                                        $lname                  = $cp_row['lname'];
                                        $uname                  = $cp_row['uname'];
                                        $email_code             = $cp_row['email_code'];
                                        $n_password             = $cp_row['n_password'];
                                        $c_password             = $cp_row['c_password'];
                                    
                                    }}}
                        ?>
                    <!-- <h5 class="card-title">Account Information</h5> -->
                    <p class="card-text">
                        <!-- Account Update Form -->
                        <form method="POST">
                        <input type="hidden" class="" id="c_password" name="c_password" value="<?php echo $c_password; ?>">
                        <input type="hidden" class="" id="n_password" name="n_password" value="<?php echo $n_password; ?>">
                            <div class="row mb-3">
                                <label for="fname" class="col-sm-2 col-form-label">User Name</label>
                                <div class="col-sm-10 w-50">
                                  <input type="hidden" class="" id="uname" name="uname" value="<?php echo $uname; ?>">
                                    <?php echo $uname; ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email_code" class="col-sm-2 col-form-label">Email Validation Code</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control w-50" id="email_code" name="email_code" required>
                                </div>
                            </div>
                            <!-- Update Button -->
                            <button type="submit" name="email_code_confirm" class="btn btn-secondary">Confirm</button>

                            




                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- END main-container -->




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>