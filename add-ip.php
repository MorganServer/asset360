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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/7kainuaawjddfzf3pj7t2fm3qdjgq5smjfjtsw3l4kqfd1h4/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo time(); ?>">

    <title>Add IP Address | Asset360</title>
</head>
<body>
    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

 <!-- main-container -->
    <div class="container" style="padding: 0 75px 0 75px;">
    <form method="POST" action="">
        <br>
        <div class="top-form" style="margin-bottom: -38px;">
            <h2 class="">Add an IP Address</h2>
            <div class="float-end" style="margin-top: -50px;">
                <button type="submit" name="add-ip" class="btn btn-primary">Submit</button>
            </div>
        </div>
        <br>
        <hr>

    
    

        <div class="row mb-3">
            <div class="col">
                <label for="ip_address" class="form-label">IP Address</label>
                <input type="text" class="form-control" id="ip_address" name="ip_address">
            </div>
            <div class="col">
                <label for="assigned_asset_tag_no" class="form-label">Asset Tag Number</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="assigned_asset_tag_no" name="assigned_asset_tag_no" value="<?php echo $asset_tag_no; ?>">
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#assetModal">
                        <i class="bi bi-search"></i> <!-- Bootstrap magnify glass icon -->
                    </button>
                </div>
            </div>
        </div>

        <div class="row mb-3">
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
                <label for="maintenance_schedule" class="form-label">Maintenance Schedule</label>
                <input type="date" class="form-control" id="maintenance_schedule" name="maintenance_schedule">
            </div>
            <div class="col">
                <label for="audit_schedule" class="form-label">Next Audit</label>
                <input type="date" class="form-control" id="audit_schedule" name="audit_schedule">
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


    <!-- Modal -->
<div class="modal fade" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assetModalLabel">Select Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Here goes the list of assets fetched from PHP -->
                <?php
                // Assume $assets is an array containing your assets
                foreach ($assets as $asset) {
                    echo '<div class="form-check">';
                    echo '<input class="form-check-input" type="radio" name="selected_asset" id="asset_' . $asset['id'] . '" value="' . $asset['id'] . '">';
                    echo '<label class="form-check-label" for="asset_' . $asset['id'] . '">' . $asset['name'] . '</label>';
                    echo '</div>';
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectAssetBtn">Select</button>
            </div>
        </div>
    </div>
</div>



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


<script>
    // JavaScript to handle selecting an asset and populating the input field
    document.getElementById('selectAssetBtn').addEventListener('click', function () {
        var selectedAssetId = document.querySelector('input[name="selected_asset"]:checked').value;
        var selectedAssetName = document.querySelector('input[name="selected_asset"]:checked + .form-check-label').textContent;
        document.getElementById('assigned_asset_tag_no').value = selectedAssetName; // Or you can assign the ID instead
        $('#assetModal').modal('hide'); // Close the modal
    });
</script>




</body>
</html>