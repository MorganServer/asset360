<?php
if (isset($_POST['add-event'])) {
    $idno = rand(1000000, 9999999);

    // Sanitize input data
    $asset_tag_no = isset($_POST['asset_tag_no']) ? mysqli_real_escape_string($conn, $_POST['asset_tag_no']) : "";
    $performed_by = isset($_POST['performed_by']) ? mysqli_real_escape_string($conn, $_POST['performed_by']) : "";
    // $reviewed_by = isset($_POST['reviewed_by']) ? mysqli_real_escape_string($conn, $_POST['reviewed_by']) : "";
    $event_type = isset($_POST['event_type']) ? mysqli_real_escape_string($conn, $_POST['event_type']) : "";
    $date_performed = isset($_POST['date_performed']) ? mysqli_real_escape_string($conn, $_POST['date_performed']) : "";
    // $date_reviewed = isset($_POST['date_reviewed']) ? mysqli_real_escape_string($conn, $_POST['date_reviewed']) : "";
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : "";
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : "";

    // Check if asset already exists
    $select = "SELECT * FROM event_log WHERE idno = '$idno'";
    $result = mysqli_query($conn, $select);
    if (mysqli_num_rows($result) > 0) {
        $error[] = 'Event already exists!';
    } else {
        // Insert the new asset into the database
        $insert = "INSERT INTO event_log (idno, asset_tag_no, event_type, performed_by, date_performed, notes, status) 
            VALUES ('$idno', NULLIF('$asset_tag_no', ''), NULLIF('$event_type', ''), NULLIF('$performed_by', ''), NULLIF('$date_performed', ''), NULLIF('$notes', ''), NULLIF('$status', ''))";

        if (mysqli_query($conn, $insert)) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit; // Ensure script stops execution after redirecting
        } else {
            $error[] = 'Error: ' . mysqli_error($conn);
        }
    }
}

?>