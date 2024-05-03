<?php
date_default_timezone_set('America/Denver');
require_once "../../app/database/connection.php";
require_once "../../path.php";
session_start();

$files = glob("../../app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}
logoutUser($conn);
if(isLoggedIn() == false) {
    header('location:' . BASE_URL . '/login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../../assets/css/styles.css?v=<?php echo time(); ?>">

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/7kainuaawjddfzf3pj7t2fm3qdjgq5smjfjtsw3l4kqfd1h4/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>Update Asset | Asset360</title>
</head>
<body>
    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

 <!-- main-container -->
    <div class="container" style="padding: 0 75px 0 75px;">
    <form method="POST" action="">
        <br>

        <?php
        $id = $_GET['id'];
        $u_sql = "SELECT * FROM assets
        WHERE asset_id = $id";
        $u_result = mysqli_query($conn, $u_sql);
        if($u_result) {
        $u_num_rows = mysqli_num_rows($u_result);
        if($u_num_rows > 0) {
            while ($u_row = mysqli_fetch_assoc($u_result)) {
                $u_id                     = $u_row['asset_id']; 
                $u_asset_name             = $u_row['asset_name']; 
                $u_asset_tag_no           = $u_row['asset_tag_no'];
                $u_manufacturer_name      = $u_row['manufacturer_name'];
                $u_model                  = $u_row['model'];
                $u_model_no               = $u_row['model_no'];
                $u_acquisition_date       = $u_row['acquisition_date'];
                $u_end_of_life_date       = $u_row['end_of_life_date'];
                $u_location               = $u_row['location'];
                $u_custodian              = $u_row['custodian'];
                $u_serial_number          = $u_row['serial_number'];
                $u_notes                  = $u_row['notes']; 
                $u_status                 = $u_row['status']; 
                $u_maintenance_schedule   = $u_row['maintenance_schedule'];
                $u_audit_schedule         = $u_row['audit_schedule']; 
                $u_asset_type             = $u_row['asset_type']; 
                $u_created_at             = $u_row['asset_created'];
                $u_updated_at             = $u_row['asset_updated'];
                $u_ip_address             = $u_row['ip_address']; 
            }
        ?>

        <div class="top-form" style="margin-bottom: -38px;">
            <h2 class="">Update <?php echo $u_asset_name; ?></h2>
            <div class="float-end" style="margin-top: -50px;">
                <button type="submit" name="update-asset" class="btn btn-primary">Submit</button>
            </div>
            <input type="hidden" class="form-control" id="asset_id" name="asset_id" value="<?php echo $u_id; ?>">
        </div>
        <br>
        <hr>

    
    

        <div class="row mb-3">
            <div class="col">
                <label for="asset_tag_no" class="form-label">Asset Tag Number</label>
                <div class="input-group">
                    <div class="input-group-text">M-</div>
                    <input type="text" class="form-control" id="asset_tag_no" name="asset_tag_no" value="<?php echo $u_asset_tag_no; ?>">
                </div>
            </div>
            <div class="col">
                <label for="asset_name" class="form-label">Asset Name</label>
                <input type="text" class="form-control" id="asset_name" name="asset_name" value="<?php echo $u_asset_name; ?>">
            </div>
            <div class="col">
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number" value="<?php echo $u_serial_number; ?>">
            </div>
            <div class="col">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model" value="<?php echo $u_model; ?>">
            </div>
            <div class="col">
                <label for="model_no" class="form-label">Model Number</label>
                <input type="text" class="form-control" id="model_no" name="model_no" value="<?php echo $u_model_no; ?>">
            </div>
            <div class="col">
                <label for="manufacturer_name" class="form-label">Manufacturer Name</label>
                <input type="text" class="form-control" id="manufacturer_name" name="manufacturer_name" value="<?php echo $u_manufacturer_name; ?>">
            </div>
            <div class="col">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location"  value="<?php echo $u_location; ?>">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label" for="asset_type">Asset Type</label>
                <select class="form-control" name="asset_type">
                    <option value="<?php echo $u_asset_type; ?>"><?php echo $u_asset_type; ?></option>
                    <option value="Server">Server</option>
                    <option value="Computer">Computer</option>
                    <option value="Network Device">Network Device</option>
                    <option value="Mobile Device">Mobile Device</option>
                    <option value="Storage Device">Storage Device</option>
                    <!-- <option value="IP Address">IP Address</option> -->
                    <option value="IOT Device">IOT Device</option>
                    <option value="Peripheral">Peripheral</option>
                </select>
            </div>
            <div class="col">
                <label class="form-label" for="status">Asset Custodian</label>
                <select class="form-control" name="custodian">
                    <option value="<?php echo $u_custodian; ?>"><?php echo $u_custodian; ?></option>
                    <?php
                    $sql = "SELECT fname, lname FROM users";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) { 
                            $first_name = $row['fname'];
                            $last_name  = $row['lname'];

                            $full_name = $first_name . " " . $last_name;
                    ?>
                        <option value="<?php echo $full_name; ?>"><?php echo $full_name; ?></option>
                    <?php } } ?>
                </select>
            </div>
            <div class="col">
                <label for="acquisition_date" class="form-label">Acquisition Date</label>
                <input type="date" class="form-control" id="acquisition_date" name="acquisition_date" value="<?php echo $u_acquisition_date; ?>">
            </div>
            <div class="col">
                <label for="end_of_life_date" class="form-label">End of Life Date</label>
                <input type="date" class="form-control" id="end_of_life_date" name="end_of_life_date" value="<?php echo $u_end_of_life_date; ?>">
            </div>
            <div class="col">
                <label class="form-label" for="status">Status</label>
                <select class="form-control" name="status">
                    <option value="<?php echo $u_status; ?>"><?php echo $u_status; ?></option>
                    <option value="In Use">In Use</option>
                    <option value="In Repair">In Repair</option>
                    <option value="In Storage">In Storage</option>
                    <option value="Disposed">Disposed</option>
                    <option value="Sold">Sold</option>
                    <option value="Sub Let">Sub Let</option>
                    <option value="Unknown">Unknown</option>
                </select>
            </div>
        </div>

        
    

        <div class="row">
            <div class="col">
                <label class="form-label" for="notes">Notes</label>
                <textarea class="form-control" name="notes" rows="5"><?php echo $u_notes; ?></textarea>
            </div>
        </div>
        <?php }} ?>
    </form>



<!-- </div> -->
 </div>
 
<!-- END main-container -->

<br><br><br>




<script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>



</body>
</html>