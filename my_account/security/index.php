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
        .wrong .fa-check {
            display: none;
        }
        .good .fa-times {
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
                    <h5 class="card-title">Account Information</h5>
                    <p class="card-text">
                        <!-- Account Type and Creation Date -->
                        <div class="mt-3">
                            <p class="text-secondary text-capitalize"><?php echo $account_type; ?> Account</p>
                            <p class="text-secondary" style="margin-top: -10px;">Member since <?php echo $f_account_created; ?></p>
                        </div>
                        <br>
                        <!-- Account Update Form -->
                        <form method="POST">
                            <div class="row mb-3">
                                <label for="fname" class="col-sm-2 col-form-label">User Name</label>
                                <div class="col-sm-10">
                                    <?php echo $uname; ?>
                                </div>
                            </div>
                            <div class="row">
                                
                              <div class="col">
                                <div class="input-group d-flex">
                                  <span
                                    class="input-group-text border-0"
                                    id="password"
                                    >
                                    <i class="bi bi-lock me-1"></i>
                                </span>
                                  <input
                                    type="password"
                                    class="form-control rounded mt-1"
                                    placeholder="Type your password"
                                    name="n_password"
                                    aria-label="password"
                                    aria-describedby="password"
                                    id="password-input"
                                  />
                                  <div class="valid-feedback"></div>
                                  <div class="invalid-feedback"></div>
                                </div>
                              </div>

                              <div class="col">

                              <div
                              data-mdb-alert-init class="alert px-4 py-3 mb-0 d-none"
                              role="alert"
                              data-mdb-color="warning"
                              id="password-alert"
                              >
                              <ul class="list-unstyled mb-0">
                                <li class="requirements leng">
                                  <i class="bi bi-check text-success me-2"></i>
                                  <i class="bi bi-x text-danger me-3"></i>
                                  Your password must have at least 8 chars</li>
                                <li class="requirements big-letter">
                                  <i class="bi bi-check text-success me-2"></i>
                                  <i class="bi bi-x text-danger me-3"></i>
                                  Your password must have at least 1 big letter.</li>
                                <li class="requirements num">
                                  <i class="bi bi-check text-success me-2"></i>
                                  <i class="bi bi-x text-danger me-3"></i>
                                  Your password must have at least 1 number.</li>
                                <li class="requirements special-char">
                                  <i class="bi bi-check text-success me-2"></i>
                                  <i class="bi bi-x text-danger me-3"></i>
                                  Your password must have at least 1 special char.</li>
                              </ul>
                              </div>
                            
                              </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-sm-2 col-form-label">Current Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="n_password" class="col-sm-2 col-form-label">New Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="n_password" name="n_password" required>
                                    <p>
                                        Your password
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="c_password" class="col-sm-2 col-form-label">Confirm Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="c_password" name="c_password" required>
                                </div>
                            </div>
                            <!-- Update Button -->
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
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
</body>
</html>