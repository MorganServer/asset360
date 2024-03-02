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
                <label class="form-label" for="status">IP Address Custodian</label>
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


<!-- Modal
<div class="modal fade" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assetModalLabel">Select Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush">
                    <?php
                    // Assuming you have a database connection established
                    // Fetch assets from the database
                    // $query = "SELECT * FROM assets";
                    // $result = mysqli_query($conn, $query);

                    // if (mysqli_num_rows($result) > 0) {
                    //     while ($row = mysqli_fetch_assoc($result)) {
                    //         ?>
                            <a href="#" class="list-group-item list-group-item-action asset-link" data-asset-id="<?php //echo $row['asset_tag_no']; ?>">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?php //echo $row['asset_tag_no']; ?></h5>
                                    <small><?php //echo $row['asset_name']; ?></small>
                                </div>
                            </a>
                            <?php
                    //     }
                    // } else {
                        ?>
                        <p class="text-muted">No assets found.</p>
                        <?php
                   //}
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectAssetBtn">Select</button>
            </div>
        </div>
    </div>
</div> -->

<div class="modal fade" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assetModalLabel">Select Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" id="assetSearchInput" class="form-control" placeholder="Search assets...">
                    <button class="btn btn-outline-secondary" type="button" id="assetSearchButton">Search</button>
                </div>
                <div class="list-group list-group-flush" id="assetList">
                    <!-- Asset list will be dynamically updated here -->
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectAssetBtn">Select</button> -->
            </div>
        </div>
    </div>
</div>




<!-- </div> -->
 </div>
 
<!-- END main-container -->

<br><br><br>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>


<script>
    // // JavaScript to handle selecting an asset and populating the input field
    // document.addEventListener('DOMContentLoaded', function () {
    //     var assetLinks = document.querySelectorAll('.asset-link');
    //     assetLinks.forEach(function (link) {
    //         link.addEventListener('click', function (event) {
    //             try {
    //                 event.preventDefault();
    //                 var selectedAssetTagNo = link.getAttribute('data-asset-id'); // Get the value of data-asset-id attribute
    //                 document.getElementById('assigned_asset_tag_no').value = selectedAssetTagNo;
    //                 $('#assetModal').modal('hide'); // Close the modal
    //             } catch (error) {
    //                 console.error('An error occurred while closing the modal:', error);
    //                 // Handle the error gracefully or log it for debugging
    //             }
    //         });
    //     });
    // });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const assetSearchInput = document.getElementById('assetSearchInput');
        const assetSearchButton = document.getElementById('assetSearchButton');
        const assetList = document.getElementById('assetList');

        // Function to fetch and display assets based on search query
        function searchAssets() {
            const searchQuery = assetSearchInput.value.trim();
            // Fetch assets based on search query from the database using AJAX or fetch API
            // Update the asset list with the fetched results
            // For demonstration, I'll assume you have a function fetchAssetsFromDatabase(searchQuery) that returns a Promise with asset data
            fetchAssetsFromDatabase(searchQuery)
                .then(data => {
                    // Clear previous search results
                    assetList.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(asset => {
                            const listItem = document.createElement('a');
                            listItem.classList.add('list-group-item', 'list-group-item-action', 'asset-link');
                            listItem.setAttribute('data-asset-id', asset.asset_tag_no);
                            listItem.innerHTML = `
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">${asset.asset_tag_no}</h5>
                                    <small>${asset.asset_name}</small>
                                </div>`;
                            assetList.appendChild(listItem);
                        });
                    } else {
                        assetList.innerHTML = '<p class="text-muted">No assets found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching assets:', error);
                });
        }

        // Event listener for search button click
        assetSearchButton.addEventListener('click', searchAssets);

        // Event listener for pressing Enter key in search input
        assetSearchInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                searchAssets();
            }
        });

        // Initially load assets without any search query
        searchAssets();
    });

    // Function to fetch assets from the database
    function fetchAssetsFromDatabase(searchQuery) {
        // Implement this function to fetch assets based on the search query from your database
        // This function should return a Promise that resolves with an array of asset objects
        // For demonstration purposes, I'll return a sample array of asset objects
        return new Promise((resolve, reject) => {
            // Assuming this is where you make the AJAX call or use fetch API to fetch assets
            // Replace this with your actual implementation
            const assets = [
                { asset_tag_no: '123', asset_name: 'Asset 1' },
                { asset_tag_no: '456', asset_name: 'Asset 2' },
                { asset_tag_no: '789', asset_name: 'Asset 3' }
                // Add more asset objects as needed
            ];
            resolve(assets); // Resolve the Promise with the fetched assets
        });
    }
</script>







</body>
</html>