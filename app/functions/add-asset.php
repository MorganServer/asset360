<?php
if (isset($_POST['add-asset'])) {
    $idno = rand(1000000, 9999999);

    // Sanitize input data

    // // Get the maximum asset_tag_no from the database
    // $sql = "SELECT MAX(asset_tag_no) AS max_tag_no FROM assets";
    // $result = mysqli_query($conn, $sql);
    // $row = mysqli_fetch_assoc($result);
    // $max_tag_no = $row['max_tag_no'];

    // // Increment the maximum asset_tag_no by 1
    // $next_tag_no = ($max_tag_no ? $max_tag_no + 1 : 4); // Start at '0004' if there's no existing data

    // // Format the asset_tag_no to have leading zeros
    // $asset_tag_no = sprintf('%04d', $next_tag_no);

    // Sanitize input data
    $tag_no = isset($_POST['asset_tag_no']) ? mysqli_real_escape_string($conn, $_POST['asset_tag_no']) : ""; 
    $asset_tag_no = "M-" . $tag_no;
    // $asset_tag_no = isset($_POST['asset_tag_no']) ? mysqli_real_escape_string($conn, $_POST['asset_tag_no']) : ""; // TRY TO MAKE THIS AUTOMATIC!!!
    $asset_name = isset($_POST['asset_name']) ? mysqli_real_escape_string($conn, $_POST['asset_name']) : "";
    $asset_type = isset($_POST['asset_type']) ? mysqli_real_escape_string($conn, $_POST['asset_type']) : "";
    $serial_number = isset($_POST['serial_number']) ? mysqli_real_escape_string($conn, $_POST['serial_number']) : "";
    $model = isset($_POST['model']) ? mysqli_real_escape_string($conn, $_POST['model']) : "";
    $model_no = isset($_POST['model_no']) ? mysqli_real_escape_string($conn, $_POST['model_no']) : "";
    $manufacturer_name = isset($_POST['manufacturer_name']) ? mysqli_real_escape_string($conn, $_POST['manufacturer_name']) : "";
    $acquisition_date = isset($_POST['acquisition_date']) ? mysqli_real_escape_string($conn, $_POST['acquisition_date']) : "";
    $end_of_life_date = isset($_POST['end_of_life_date']) ? mysqli_real_escape_string($conn, $_POST['end_of_life_date']) : "";
    $location = isset($_POST['location']) ? mysqli_real_escape_string($conn, $_POST['location']) : "";
    $custodian = isset($_POST['custodian']) ? mysqli_real_escape_string($conn, $_POST['custodian']) : "";
    // $maintenance_schedule = isset($_POST['maintenance_schedule']) ? mysqli_real_escape_string($conn, $_POST['maintenance_schedule']) : "";
    // $audit_schedule = isset($_POST['audit_schedule']) ? mysqli_real_escape_string($conn, $_POST['audit_schedule']) : ""; 
    $audit_schedule = date('Y-m-d', strtotime('+3 months'));
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : "";
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : "";

    // Check if asset already exists
    $select = "SELECT * FROM assets WHERE idno = '$idno'";
    $result = mysqli_query($conn, $select);
    if (mysqli_num_rows($result) > 0) {
        $error[] = 'Asset already exists!';
    } else {
        // Insert the new asset into the database
        $insert = "INSERT INTO assets (idno, asset_tag_no, asset_name, asset_type, serial_number, model, model_no,manufacturer_name, acquisition_date, end_of_life_date, location, custodian, audit_schedule, notes, status) 
            VALUES ('$idno', NULLIF('$asset_tag_no', ''), NULLIF('$asset_name', ''), NULLIF('$asset_type', ''), NULLIF('$serial_number', ''), NULLIF('$model', ''), NULLIF('$model_no', ''), NULLIF('$manufacturer_name', ''), NULLIF('$acquisition_date', ''), NULLIF('$end_of_life_date', ''), NULLIF('$location', ''), NULLIF('$custodian', ''), NULLIF('$audit_schedule', ''), NULLIF('$notes', ''), NULLIF('$status', ''))";

        if (mysqli_query($conn, $insert)) {
            header('location:' . BASE_URL . '/');
            exit; // Ensure script stops execution after redirecting
        } else {
            $error[] = 'Error: ' . mysqli_error($conn);
        }
    }
}

?>