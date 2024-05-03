<?php
if (isset($_POST['reschedule'])) {

    $asset_id = isset($_POST['asset_id']) ? mysqli_real_escape_string($conn, $_POST['asset_id']) : "";
    $audit_schedule = isset($_POST['audit_schedule']) ? mysqli_real_escape_string($conn, $_POST['audit_schedule']) : "";

    $insert = "UPDATE assets SET audit_schedule = NULLIF('$audit_schedule', '') WHERE asset_id = '$asset_id'";

    if (mysqli_query($conn, $insert)) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        $error[] = 'Error: ' . mysqli_error($conn);
    }
}
?>
