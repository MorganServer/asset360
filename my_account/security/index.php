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
        <h2 class="mt-4">Change Password</h2>
        <hr>

        <!-- Information -->
        <div class="mb-4" style="background-color: rgb(229, 233, 236); border-radius: 5px; padding: 10px;">
            <!-- Steps -->
            <p>You may change your password at any time by following the steps below:</p>
            <ul>
                <li>Enter your current password and new password. You will need to enter the new password twice in order to confirm that you have entered it correctly.</li>
                <li>An email with a confirmation number will be sent to your email address on record. Enter the confirmation number on the next page.</li>
                <li>The password change will be effective immediately.</li>
            </ul>
        </div>

        <!-- Account Information -->
        <div class="col">
            <div class="card w-100" style="border-top: 4px solid rgb(0, 43, 73); border-radius: 3px !important;">
                <div class="card-body p-4">
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
                    <?php echo $error_message; ?>
                    <p class="card-text">
                        <!-- Account Update Form -->
                        <form method="POST">
                            <div class="row mb-3">
                                <label for="uname" class="col-sm-2 col-form-label">User Name</label>
                                <div class="col-sm-10 w-50">
                                    <input type="hidden" class="" id="uname" name="uname" value="<?php echo $uname; ?>">
                                    <?php echo $uname; ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-sm-2 col-form-label">Current Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control w-50" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                              <label for="n_password" class="col-sm-2 col-form-label">New Password</label>
                              <div class="col-sm-10">
                                <div class="input-group w-50">
                                  <input
                                    type="password"
                                    class="form-control rounded mt-1"
                                    aria-label="password"
                                    aria-describedby="password"
                                    id="password-input"
                                    name="n_password"
                                  />
                                  <!-- <div class="valid-feedback">Good</div>
                                  <div class="invalid-feedback">Wrong</div> -->
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-6 mt-2 mt-xxl-0 w-auto h-auto mb-2" style="margin-left: 220px;">
                                <div
                                  data-mdb-alert-init
                                  class="alert px-4 py-3 mb-0 d-none"
                                  role="alert"
                                  data-mdb-color="warning"
                                  id="password-alert"
                                >
                                  <ul class="list-unstyled mb-0">
                                    <li class="requirements leng">
                                      <i class="bi bi-check text-success me-2"></i>
                                      <i class="bi bi-x text-danger me-3"></i>
                                      Your password must have at least 8 characters.
                                    </li>
                                    <li class="requirements big-letter">
                                      <i class="bi bi-check text-success me-2"></i>
                                      <i class="bi bi-x text-danger me-3"></i>
                                      Your password must have at least 1 uppercase letter.
                                    </li>
                                    <li class="requirements num">
                                      <i class="bi bi-check text-success me-2"></i>
                                      <i class="bi bi-x text-danger me-3"></i>
                                      Your password must have at least 1 number.
                                    </li>
                                    <li class="requirements special-char">
                                      <i class="bi bi-check text-success me-2"></i>
                                      <i class="bi bi-x text-danger me-3"></i>
                                      Your password must have at least 1 special character.
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>





                            <div class="row mb-3">
                                <label for="c_password" class="col-sm-2 col-form-label">Confirm Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control w-50" id="c_password" name="c_password" required>
                                </div>
                            </div>


                            <!-- Update Button -->
                            <button type="submit" name="change" class="btn btn-secondary">Change Password</button>

                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- END main-container -->



    <script>
        addEventListener("DOMContentLoaded", (event) => {
  const password = document.getElementById("password-input");
  const passwordAlert = document.getElementById("password-alert");
  const requirements = document.querySelectorAll(".requirements");
  const leng = document.querySelector(".leng");
  const bigLetter = document.querySelector(".big-letter");
  const num = document.querySelector(".num");
  const specialChar = document.querySelector(".special-char");

  requirements.forEach((element) => element.classList.add("wrong"));

  password.addEventListener("focus", () => {
      passwordAlert.classList.remove("d-none");
      if (!password.classList.contains("is-valid")) {
          password.classList.add("is-invalid");
      }
  });

  password.addEventListener("input", () => {
      const value = password.value;
      const isLengthValid = value.length >= 8;
      const hasUpperCase = /[A-Z]/.test(value);
      const hasNumber = /\d/.test(value);
      const hasSpecialChar = /[!@#$%^&*()\[\]{}\\|;:'",<.>/?`~]/.test(value);

      leng.classList.toggle("good", isLengthValid);
      leng.classList.toggle("wrong", !isLengthValid);
      bigLetter.classList.toggle("good", hasUpperCase);
      bigLetter.classList.toggle("wrong", !hasUpperCase);
      num.classList.toggle("good", hasNumber);
      num.classList.toggle("wrong", !hasNumber);
      specialChar.classList.toggle("good", hasSpecialChar);
      specialChar.classList.toggle("wrong", !hasSpecialChar);

      const isPasswordValid = isLengthValid && hasUpperCase && hasNumber && hasSpecialChar;

      if (isPasswordValid) {
          password.classList.remove("is-invalid");
          password.classList.add("is-valid");

          requirements.forEach((element) => {
              element.classList.remove("wrong");
              element.classList.add("good");
          });

          passwordAlert.classList.remove("alert-warning");
          passwordAlert.classList.add("alert-success");
      } else {
          password.classList.remove("is-valid");
          password.classList.add("is-invalid");

          passwordAlert.classList.add("alert-warning");
          passwordAlert.classList.remove("alert-success");
      }
  });

  password.addEventListener("blur", () => {
      passwordAlert.classList.add("d-none");
  });
});
    </script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>