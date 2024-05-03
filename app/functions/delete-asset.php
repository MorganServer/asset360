<?php
include_once '../../path.php'; // Assuming this defines BASE_URL
include_once BASE_URL . '/app/database/connection.php';

if(isset($_GET['id'])) {
    // Sanitize the ID to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Construct the SQL query
    $sql = "DELETE FROM assets WHERE asset_id='$id'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Redirect back to the page from where delete was initiated
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        // Handle errors if any
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Handle the case when id is not set in the URL
    echo "ID not provided.";
}
?>
