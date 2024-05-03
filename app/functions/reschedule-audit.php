<?php
if (isset($_POST['reschedule'])) {
    $idno = rand(1000000, 9999999);

    // Sanitize input data
    $asset_id = isset($_POST['asset_id']) ? mysqli_real_escape_string($conn, $_POST['asset_id']) : "";
    // $asset_tag_no = isset($_POST['asset_tag_no']) ? mysqli_real_escape_string($conn, $_POST['asset_tag_no']) : "";
    $audit_schedule = isset($_POST['audit_schedule']) ? mysqli_real_escape_string($conn, $_POST['audit_schedule']) : "";

    // Check if asset already exists
    $select = "SELECT * FROM assets WHERE idno = '$idno'";
    $result = mysqli_query($conn, $select);
    if (mysqli_num_rows($result) > 0) {
        $error[] = 'Event already exists!';
    } else {
        // Insert the new asset into the database
        $insert = "UPDATE assets SET audit_schedule = NULLIF('$audit_schedule', '') WHERE asset_id = '$asset_id'";

        if (mysqli_query($conn, $insert)) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit; // Ensure script stops execution after redirecting
        } else {
            $error[] = 'Error: ' . mysqli_error($conn);
        }
    }
}

?>