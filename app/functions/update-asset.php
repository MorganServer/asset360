<?php
if (isset($_POST['update-asset'])) {
    // Sanitize input data
    $idno = isset($_POST['idno']) ? mysqli_real_escape_string($conn, $_POST['idno']) : "";
    $tag_no = isset($_POST['asset_tag_no']) ? mysqli_real_escape_string($conn, $_POST['asset_tag_no']) : ""; 
    $asset_tag_no = "M-" . $tag_no;
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
    $audit_schedule = date('Y-m-d', strtotime('+1 months'));
    $notes = isset($_POST['notes']) ? mysqli_real_escape_string($conn, $_POST['notes']) : "";
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : "";

    // Update the asset in the database
    $update = "UPDATE assets SET
                asset_tag_no = NULLIF('$asset_tag_no', ''),
                asset_name = NULLIF('$asset_name', ''),
                asset_type = NULLIF('$asset_type', ''),
                serial_number = NULLIF('$serial_number', ''),
                model = NULLIF('$model', ''),
                model_no = NULLIF('$model_no', ''),
                manufacturer_name = NULLIF('$manufacturer_name', ''),
                acquisition_date = NULLIF('$acquisition_date', ''),
                end_of_life_date = NULLIF('$end_of_life_date', ''),
                location = NULLIF('$location', ''),
                custodian = NULLIF('$custodian', ''),
                audit_schedule = NULLIF('$audit_schedule', ''),
                notes = NULLIF('$notes', ''),
                status = NULLIF('$status', '')
              WHERE asset_id = '$id'";

    if (mysqli_query($conn, $update)) {
        header('location:' . BASE_URL . '/');
        exit; // Ensure script stops execution after redirecting
    } else {
        $error[] = 'Error: ' . mysqli_error($conn);
    }
}

?>
