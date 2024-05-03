<?php

// Include the necessary files
include_once ROOT_PATH . '/app/database/connection.php';

// Check if ID is provided in the URL
if(isset($_GET['id'])) {
    // Sanitize the ID to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Construct the SQL query
    $d_sql = "DELETE FROM assets WHERE asset_id='$id'";

    // Execute the query
    if (mysqli_query($conn, $d_sql)) {
        // Redirect back to the page from where delete was initiated
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        // Handle errors if any
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    // Handle the case when id is not set in the URL
    // echo "ID not provided.";
}
?>
