<?php
date_default_timezone_set('America/Denver');
require_once "../../app/database/connection.php";
// require_once "app/functions/add_app.php";
require_once "../../path.php";
session_start();

$files = glob("../../app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/7kainuaawjddfzf3pj7t2fm3qdjgq5smjfjtsw3l4kqfd1h4/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../../assets/css/styles.css?v=<?php echo time(); ?>">

    <title>Home | Asset360</title>
</head>
<body>
    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

 <!-- main-container -->
    <div class="container" style="padding: 0 75px 0 75px;">
    <form method="POST" action="">
        <br>
        <div class="top-form" style="margin-bottom: -38px;">
            <h2 class="">Add an Asset</h2>
            <div class="float-end" style="margin-top: -50px;">
                <button type="submit" name="add-asset" class="btn btn-primary">Submit</button>
            </div>
        </div>
        <br>
        <hr>

    
    

        <div class="row mb-3">
            <div class="col">
                <label for="asset_tag_no" class="form-label">Asset Tag Number</label>
                <div class="input-group">
                    <div class="input-group-text">M-</div>
                    <input type="text" class="form-control" id="asset_tag_no" name="asset_tag_no">
                </div>
            </div>
            <div class="col">
                <label for="asset_name" class="form-label">Asset Name</label>
                <input type="text" class="form-control" id="asset_name" name="asset_name">
            </div>
            <div class="col">
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number">
            </div>
            <div class="col">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model">
            </div>
            <div class="col">
                <label for="model_no" class="form-label">Model Number</label>
                <input type="text" class="form-control" id="model_no" name="model_no">
            </div>
            <div class="col">
                <label for="manufacturer_name" class="form-label">Manufacturer Name</label>
                <input type="text" class="form-control" id="manufacturer_name" name="manufacturer_name">
            </div>
            <div class="col">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label" for="asset_type">Asset Type</label>
                <select class="form-control" name="asset_type">
                    <option value="">Select an option...</option>
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
                    <option value="">Select an option...</option>
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
                <input type="date" class="form-control" id="acquisition_date" name="acquisition_date">
            </div>
            <div class="col">
                <label for="end_of_life_date" class="form-label">End of Life Date</label>
                <input type="date" class="form-control" id="end_of_life_date" name="end_of_life_date">
            </div>
            <!-- <div class="col">
                <label for="maintenance_schedule" class="form-label">Maintenance Schedule</label>
                <input type="date" class="form-control" id="maintenance_schedule" name="maintenance_schedule">
            </div> -->
            <?php
                $audit_schedule = date('M d, Y', strtotime('+1 months'));
            ?>
            <div class="col">
                <label for="audit_schedule" class="form-label">First Audit</label>
                <input type="text" class="form-control" id="audit_schedule" name="audit_schedule" readonly value="<?php echo $audit_schedule; ?>">
            </div>
            <div class="col">
                <label class="form-label" for="status">Status</label>
                <select class="form-control" name="status">
                    <option value="">Select an option...</option>
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
                <textarea class="form-control" name="notes" rows="5"></textarea>
            </div>
        </div>
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