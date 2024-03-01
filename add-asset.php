<?php
date_default_timezone_set('America/Denver');
require_once "app/database/connection.php";
// require_once "app/functions/add_app.php";
require_once "path.php";
session_start();

$files = glob("app/functions/*.php");
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
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/7kainuaawjddfzf3pj7t2fm3qdjgq5smjfjtsw3l4kqfd1h4/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <title>Add Asset | Asset360</title>

    <style>
        
    </style>
    
</head>
<body>

<?php include(ROOT_PATH . "/app/includes/header.php"); ?>
<?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

 <!-- main-container -->
 <div class="container">
 <div class="container-fluid main" style="background-color: rgb(240, 240, 240); max-width: 80%; border-radius: 15px;">

<br><br>

    <form method="POST" action="">

        <div class="row mb-3">
            <div class="col">
                <label for="asset_tag_no" class="form-label">Asset Tag Number</label>
                <input type="text" class="form-control" id="asset_tag_no" name="asset_tag_no">
            </div>
            <div class="col">
                <label for="asset_name" class="form-label">Asset Name</label>
                <input type="text" class="form-control" id="asset_name" name="asset_name">
            </div>
            <div class="col">
                <label class="form-label" for="status">Asset Type</label>
                <select class="form-control" name="asset_type">
                    <option value="">Select an option...</option>
                    <option value="Server">Server</option>
                    <option value="Laptop">Laptop</option>
                    <option value="Network">Network</option>
                    <option value="Mobile Device">Mobile Device</option>
                    <option value="Storage">Storage</option>
                    <option value="Peripheral">Peripheral</option>
                    <option value="IOT Device">IOT Device</option>
                    <option value="Accessories">Accessories</option>
                </select>
            </div>
            <div class="col">
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model">
            </div>
            <div class="col">
                <label for="model_no" class="form-label">Model Number</label>
                <input type="text" class="form-control" id="model_no" name="model_no">
            </div>
            <div class="col">
                <label for="acquisition_date" class="form-label">Acquisition Date</label>
                <input type="text" class="form-control" id="acquisition_date" name="acquisition_date">
            </div>
            <div class="col">
                <label for="end_of_life_date" class="form-label">End of Life Date</label>
                <input type="text" class="form-control" id="end_of_life_date" name="end_of_life_date">
            </div>
        </div>
    
        <div class="row mb-3">
            <div class="col">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location">
            </div>
            <div class="col">
                <label class="form-label" for="status">Asset Custodian  <span class="text-muted" style="font-size: 10px;">Responsible for asset</span></label>
                <select class="form-control" name="asset_type">
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
                <label for="maintenance_schedule" class="form-label">Maintenance Schedule</label>
                <input type="date" class="form-control" id="maintenance_schedule" name="maintenance_schedule">
            </div>
            <div class="col">
                <label for="audit_schedule" class="form-label">Next Audit</label>
                <input type="date" class="form-control" id="audit_schedule" name="audit_schedule">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label" for="notes">Notes</label>
                <textarea class="form-control" name="notes" rows="5"></textarea>
            </div>
        </div>

        <!-- <div class="row mb-3 ps-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="watchlist" name="watchlist" value="1">
                <label class="form-check-label" for="watchlist">Add to Watchlist</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="interview_set" name="interview_set" value="1">
                <label class="form-check-label" for="interview_set">Interview Set</label>
            </div>
        </div> -->


    
        <button type="submit" name="add-full" class="btn btn-primary">Submit</button>
        <div class="pb-4"></div>
    </form>



</div>
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