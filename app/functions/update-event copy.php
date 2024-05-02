<?php
if (isset($_POST['acknowledge'])) {
    // Sanitize input data
    $notification_id = isset($_POST['notification_id']) ? mysqli_real_escape_string($conn, $_POST['notification_id']) : "";

    // Update the notification to mark it as acknowledged
    $update_query = "UPDATE notifications SET acknowledged = 1 WHERE id = '$notification_id'";

    if (mysqli_query($conn, $update_query)) {
        // Notification acknowledged successfully
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit; // Ensure script stops execution after redirecting
    } else {
        // Error updating notification
        $error_message = 'Error: ' . mysqli_error($conn);
        // You might want to handle this error case accordingly, such as displaying an error message to the user
    }
}
?>
