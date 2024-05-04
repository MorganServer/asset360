<?php
// Assuming you have a database connection established already

// Step 1: Validate current password
if (isset($_POST['change'])) {
    $uname = $_SESSION['uname']; // Assuming you have stored the username in a session
    $password = $_POST['password'];
    // Query to check if the current password matches
    $query = "SELECT * FROM users WHERE uname='$uname' AND password='$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        // Step 2: Validate new password and confirm password match
        $new_password = $_POST['n_password'];
        $confirm_password = $_POST['c_password'];
        if ($new_password == $confirm_password) {
            // Step 3: Generate random 8-digit code
            $code = mt_rand(10000000, 99999999);
            // Step 4: Store code in the database
            $update_query = "UPDATE users SET email_code='$code' WHERE uname='$uname'";
            mysqli_query($conn, $update_query);
            // Step 5: Send code to the user via email (you need to implement this)
            // Step 6: Display modal for code confirmation (you need to implement this)
        } else {
            echo "New password and confirm password do not match.";
        }
    } else {
        echo "Invalid current password.";
    }
}

// Step 7: If code is validated, update the password
if (isset($_POST['confirm_code'])) {
    $uname = $_SESSION['uname']; // Assuming you have stored the username in a session
    $code_entered = $_POST['code_entered'];
    // Query to check if the entered code matches
    $code_query = "SELECT * FROM users WHERE uname='$uname' AND email_code='$code_entered'";
    $code_result = mysqli_query($conn, $code_query);
    if (mysqli_num_rows($code_result) == 1) {
        $new_password = $_POST['n_password'];
        // Update the password in the database
        $update_password_query = "UPDATE users SET password='$new_password' WHERE uname='$uname'";
        mysqli_query($conn, $update_password_query);
        echo "Password updated successfully.";
    } else {
        echo "Invalid code.";
    }
}
?>
