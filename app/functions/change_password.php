<?php


// Check if the form is submitted
if(isset($_POST['change'])) {
    // Get current password from the form
    $uname = isset($_POST['uname']) ? mysqli_real_escape_string($conn, $_POST['uname']) : "";

    $current_password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : "";
    $h_current_password = md5($current_password);

    $new_password = isset($_POST['n_password']) ? mysqli_real_escape_string($conn, $_POST['n_password']) : "";
    $h_new_password = md5($new_password);

    $confirm_password = isset($_POST['c_password']) ? mysqli_real_escape_string($conn, $_POST['c_password']) : "";
    $h_confirm_password = md5($confirm_password);

    $query = "SELECT * FROM users WHERE uname = '$uname' AND password = '$h_current_password'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if(!$user) {
        $error_message = "Current password is incorrect. Please try again.";
    } else if ($h_new_password != $h_confirm_password) {
        $error_message = "New passwords don't match. Please try again."; 
    } else {
        // Generate a random 8-digit code
        $random_code = mt_rand(10000000, 99999999);

        // Update the user's row in the database with the new password and the generated code
        $update_query = "UPDATE users SET n_password = '$h_new_password', c_password = '$h_confirm_password', email_code = '$random_code' WHERE uname = '$uname'";
        mysqli_query($conn, $update_query);

        // Call the Python script to send the email
        $recipient_email = $user['email']; // Assuming 'email' is the column name in your database for the user's email address
        // $python_script_path = BASE_URL . "/app/backend_scripts/script.py"; // Replace this with the path to your Python script
        // $command = "python3 $python_script_path $recipient_email $random_code";
        $python_path = "/usr/bin/python3"; // Example path to python3
        $python_script_path = "/var/www/asset360/public_html/asset360/app/backend_scripts/email_code.py"; // Replace this with the actual path
        $recipient_email = escapeshellarg($recipient_email); // Escape recipient email to prevent shell injection
        $random_code = escapeshellarg($random_code); // Escape random code to prevent shell injection
        $command = "sudo $python_path $python_script_path $recipient_email $random_code";
        exec($command, $output, $return_status);

        if ($return_status !== 0) {
            // Command failed
            echo "Failed to execute Python script.";
            print_r($output); // Output any error messages
        } else {
            // Command succeeded
            echo "Python script executed successfully.";
        }

        // Redirect to the next page
        // header("Location: " . BASE_URL . "/my_account/security/email_code_change.php");
        exit(); // Ensure script execution stops after redirecting
    }
}





// Step 7: If code is validated, update the password



if (isset($_POST['email_code_confirm'])) {
    
    $uname = isset($_POST['uname']) ? mysqli_real_escape_string($conn, $_POST['uname']) : "";
    $new_password = isset($_POST['n_password']) ? mysqli_real_escape_string($conn, $_POST['n_password']) : "";
    $email_code = isset($_POST['email_code']) ? mysqli_real_escape_string($conn, $_POST['email_code']) : "";
 
    $code_query = "SELECT * FROM users WHERE uname='$uname' AND email_code='$email_code'";
    $code_result = mysqli_query($conn, $code_query);
    if (mysqli_num_rows($code_result) == 1) {
        // Update the password in the database
        $update_password_query = "UPDATE users SET password='$new_password', n_password = NULL, c_password = NULL, email_code = NULL WHERE uname='$uname'";
        mysqli_query($conn, $update_password_query);
        // echo "Password updated successfully.";
        header("Location: " . BASE_URL . "/my_account/index.php");
    } else {
        echo "Invalid code.";
    }
}
?>
