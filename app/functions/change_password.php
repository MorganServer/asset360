<?php
// Assuming you have a database connection established already

// Step 1: Validate current password
// if (isset($_POST['change'])) {
//     $uname = $_SESSION['uname']; // Assuming you have stored the username in a session
//     $password = md5($_POST['password']); // Hash the current password
//     // Query to check if the current password matches
//     $query = "SELECT * FROM users WHERE uname='$uname' AND password='$password'";
//     $result = mysqli_query($conn, $query);
//     if (mysqli_num_rows($result) == 1) {
//         // Step 2: Validate new password and confirm password match
//         $new_password = $_POST['n_password'];
//         $confirm_password = $_POST['c_password'];
//         if ($new_password == $confirm_password) {
//             // Step 3: Generate random 8-digit code
//             $code = mt_rand(10000000, 99999999);
//             // Step 4: Store code in the database
//             $update_query = "UPDATE users SET email_code='$code' WHERE uname='$uname'";
//             mysqli_query($conn, $update_query);
//             // Step 5: Send code to the user via email (you need to implement this)
//             // Step 6: Display modal for code confirmation (you need to implement this)
//         } else {
//             echo "New password and confirm password do not match.";
//         }
//     } else {
//         echo "Invalid current password.";
//     }
// }

// Assuming you have already established a database connection

// Check if the form is submitted
if(isset($_POST['change'])) {
    // Get current password from the form
    $uname = isset($_POST['uname']) ? mysqli_real_escape_string($conn, $_POST['uname']) : "";

    $current_password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : "";


    // $new_password = $_POST['n_password'];
    // // Get confirmed password from the form
    // $confirmed_password = $_POST['c_password'];

    // Check if current password matches the one stored in the database
    // Replace 'YOUR_TABLE_NAME' with the name of your users table
    $query = "SELECT * FROM users WHERE uname = '$uname' AND password = '$current_password'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if(!$user) {
        // Current password doesn't match, show an error or redirect back to the form
        // For simplicity, I'm just printing an error message here
        echo "Current password is incorrect.";
    // } elseif($new_password !== $confirmed_password) {
    //     // New password and confirmed password don't match
    //     echo "New password and confirmed password don't match.";
    } else {
        // Generate a random 8-digit code
        $random_code = mt_rand(10000000, 99999999);

        // Update the user's row in the database with the new password and the generated code
        $update_query = "UPDATE users SET email_code = '$random_code' WHERE uname = '$uname'";
        mysqli_query($conn, $update_query);

        // Redirect to the next page
        header("Location: " . BASE_URL . "/my_account/security/email_code_change.php");
        exit(); // Ensure script execution stops after redirecting
    }
}





// Step 7: If code is validated, update the password



// if (isset($_POST['email_code_confirm'])) {
//     $uname = $_SESSION['uname']; // Assuming you have stored the username in a session
//     $code_entered = $_POST['email_code']; // Assuming the form field name is 'email_code'
//     // Query to check if the entered code matches
//     $code_query = "SELECT * FROM users WHERE uname='$uname' AND email_code='$code_entered'";
//     $code_result = mysqli_query($conn, $code_query);
//     if (mysqli_num_rows($code_result) == 1) {
//         $new_password = md5($_POST['n_password']); // Hash the new password
//         // Update the password in the database
//         $update_password_query = "UPDATE users SET password='$new_password' WHERE uname='$uname'";
//         mysqli_query($conn, $update_password_query);
//         echo "Password updated successfully.";
//     } else {
//         echo "Invalid code.";
//     }
// }
?>
