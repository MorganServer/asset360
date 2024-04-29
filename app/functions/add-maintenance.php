<?php
if (isset($_POST['add-event'])) {
    $idno = rand(1000000, 9999999);

    // Sanitize input data
    $asset_tag_no = isset($_POST['asset_tag_no']) ? mysqli_real_escape_string($conn, $_POST['asset_tag_no']) : "";
    $requested_by = isset($_POST['requested_by']) ? mysqli_real_escape_string($conn, $_POST['requested_by']) : "";
    $completed_by = isset($_POST['completed_by']) ? mysqli_real_escape_string($conn, $_POST['completed_by']) : "";
    $event_type = isset($_POST['event_type']) ? mysqli_real_escape_string($conn, $_POST['event_type']) : "";
    $date_requested = isset($_POST['date_requested']) ? mysqli_real_escape_string($conn, $_POST['date_requested']) : "";
    $date_completed = isset($_POST['date_completed']) ? mysqli_real_escape_string($conn, $_POST['date_completed']) : "";
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : "";
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : "";

    // Check if asset already exists
    $select = "SELECT * FROM event_log WHERE idno = '$idno'";
    $result = mysqli_query($conn, $select);
    if (mysqli_num_rows($result) > 0) {
        $error[] = 'Event already exists!';
    } else {
        // Insert the new asset into the database
        $insert = "INSERT INTO event_log (idno, asset_tag_no, requested_by, completed_by, event_type, date_requested, date_completed, notes, status) 
            VALUES ('$idno', NULLIF('$asset_tag_no', ''), NULLIF('$requested_by', ''), NULLIF('$completed_by', ''), NULLIF('$event_type', ''), NULLIF('$date_requested', ''), NULLIF('$date_completed', ''), NULLIF('$notes', ''), NULLIF('$status', ''))";

        if (mysqli_query($conn, $insert)) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit; // Ensure script stops execution after redirecting
        } else {
            $error[] = 'Error: ' . mysqli_error($conn);
        }
    }
}

?>