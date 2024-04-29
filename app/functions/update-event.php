<?php
if (isset($_POST['review-event'])) {
    $idno = rand(1000000, 9999999);

    // Sanitize input data
    $event_id = isset($_POST['event_id']) ? mysqli_real_escape_string($conn, $_POST['event_id']) : "";
    // $asset_tag_no = isset($_POST['asset_tag_no']) ? mysqli_real_escape_string($conn, $_POST['asset_tag_no']) : "";
    $reviewed_by = isset($_POST['reviewed_by']) ? mysqli_real_escape_string($conn, $_POST['reviewed_by']) : "";
    $date_reviewed = isset($_POST['date_reviewed']) ? mysqli_real_escape_string($conn, $_POST['date_reviewed']) : "";
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : "";
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : "";

    // Get the existing notes from the database
$sqlSelect = "SELECT notes FROM event_log WHERE event_id = '$event_id'";
$result = mysqli_query($conn, $sqlSelect);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $existingNotes = $row['notes'];
} else {
    $existingNotes = "";
}

// Concatenate the new notes with the existing notes, adding a newline character for separation if needed
$newNotes = isset($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : "";
if (!empty($existingNotes) && !empty($newNotes)) {
    $combinedNotes = $newNotes . "\n" . $existingNotes;
} else {
    $combinedNotes = $newNotes . $existingNotes;
}

    // Check if asset already exists
    $select = "SELECT * FROM event_log WHERE idno = '$idno'";
    $result = mysqli_query($conn, $select);
    if (mysqli_num_rows($result) > 0) {
        $error[] = 'Event already exists!';
    } else {
        // Insert the new asset into the database
        $insert = "UPDATE event_log SET reviewed_by = NULLIF('$reviewed_by', ''), date_reviewed = NULLIF('$date_reviewed', ''), notes = '$combinedNotes', status = NULLIF('$status', '') WHERE event_id = '$event_id'";

        if (mysqli_query($conn, $insert)) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit; // Ensure script stops execution after redirecting
        } else {
            $error[] = 'Error: ' . mysqli_error($conn);
        }
    }
}

?>