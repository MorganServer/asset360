<?php
if (isset($_POST['add-ip'])) {
    $idno = rand(1000000, 9999999);

    // Sanitize input data
    $assigned_asset_tag_no = isset($_POST['assigned_asset_tag_no']) ? mysqli_real_escape_string($conn, $_POST['assigned_asset_tag_no']) : "";
    $ip_address = isset($_POST['ip_address']) ? mysqli_real_escape_string($conn, $_POST['ip_address']) : "";
    $custodian = isset($_POST['custodian']) ? mysqli_real_escape_string($conn, $_POST['custodian']) : "";
    $maintenance_schedule = isset($_POST['maintenance_schedule']) ? mysqli_real_escape_string($conn, $_POST['maintenance_schedule']) : "";
    $audit_schedule = isset($_POST['audit_schedule']) ? mysqli_real_escape_string($conn, $_POST['audit_schedule']) : "";
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : "";
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : "";

    // Check if asset already exists
    $select = "SELECT * FROM ip_address WHERE idno = '$idno'";
    $result = mysqli_query($conn, $select);
    if (mysqli_num_rows($result) > 0) {
        $error[] = 'Asset already exists!';
    } else {
        // Insert the new asset into the database
        $insert = "INSERT INTO ip_address (idno, assigned_asset_tag_no, ip_address, custodian, maintenance_schedule, audit_schedule, notes, status) 
            VALUES ('$idno', NULLIF('$assigned_asset_tag_no', ''), NULLIF('$ip_address', ''), NULLIF('$custodian', ''), NULLIF('$maintenance_schedule', ''), NULLIF('$audit_schedule', ''), NULLIF('$notes', ''), NULLIF('$status', ''))";

        if (mysqli_query($conn, $insert)) {
            header('location:' . BASE_URL . '/');
            exit; // Ensure script stops execution after redirecting
        } else {
            $error[] = 'Error: ' . mysqli_error($conn);
        }
    }
}

?>