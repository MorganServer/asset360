<?php

if (isset($_POST['add-asset'])) {
	$idno  = rand(1000000, 9999999);
	if(isset($_POST['asset_tag_no'])) { $asset_tag_no = mysqli_real_escape_string($conn, $_POST['asset_tag_no']); } else { $asset_tag_no = ""; }
	if(isset($_POST['asset_name'])) { $asset_name = mysqli_real_escape_string($conn, $_POST['asset_name']); } else { $asset_name = ""; }
	if(isset($_POST['asset_type'])) { $asset_type = mysqli_real_escape_string($conn, $_POST['asset_type']); } else { $asset_type = ""; }
	if(isset($_POST['serial_number'])) { $serial_number = mysqli_real_escape_string($conn, $_POST['serial_number']); } else { $serial_number = ""; }
	if(isset($_POST['model'])) { $model = mysqli_real_escape_string($conn, $_POST['model']); } else { $model = ""; }
	if(isset($_POST['model_no'])) { $model_no = mysqli_real_escape_string($conn, $_POST['model_no']); } else { $model_no = ""; }
	if(isset($_POST['acquisition_date'])) { $acquisition_date = mysqli_real_escape_string($conn, $_POST['acquisition_date']); } else { $acquisition_date = ""; }
	if(isset($_POST['end_of_life_date'])) { $end_of_life_date = mysqli_real_escape_string($conn, $_POST['end_of_life_date']); } else { $end_of_life_date = ""; }
	if(isset($_POST['location'])) { $location = mysqli_real_escape_string($conn, $_POST['location']); } else { $location = ""; }
	if(isset($_POST['custodian'])) { $custodian = mysqli_real_escape_string($conn, $_POST['custodian']); } else { $custodian = ""; }
	if(isset($_POST['maintenance_schedule'])) { $maintenance_schedule = mysqli_real_escape_string($conn, $_POST['maintenance_schedule']); } else { $maintenance_schedule = ""; }
	if(isset($_POST['audit_schedule'])) { $audit_schedule = mysqli_real_escape_string($conn, $_POST['audit_schedule']); } else { $audit_schedule = ""; }
    if(isset($_POST['notes'])) { $notes = mysqli_real_escape_string($conn, $_POST['notes']); } else { $notes = ""; }
	if(isset($_POST['status'])) { $status = mysqli_real_escape_string($conn, $_POST['status']); } else { $status = ""; }
		

	$select = "SELECT * FROM assets WHERE idno = '$idno'";
	$result = mysqli_query($conn, $select);

	if (mysqli_num_rows($result) > 0) {
		$error[] = 'Application already exists!';
	} else {
		$insert = "INSERT INTO applications (idno, asset_tag_no, asset_name, asset_type, serial_number, model, model_no, acquisition_date, end_of_life_date, location, custodian, maintenance_schedule, audit_schedule, notes, status) VALUES ('$idno', NULLIF('$asset_tag_no', ''), NULLIF('$asset_name', ''), NULLIF('$asset_type', ''), NULLIF('$serial_number', ''), NULLIF('$model', ''), NULLIF('$model_no', ''), NULLIF('$acquisition_date', ''), NULLIF('$end_of_life_date', ''), NULLIF('$location', ''), NULLIF('$custodian', ''), NULLIF('$maintenance_schedule', ''), NULLIF('$audit_schedule', ''), NULLIF('$notes', ''), NULLIF('$status', ''))";
		mysqli_query($conn, $insert);
		header('location:' . BASE_URL . '/');
	}
}

?>