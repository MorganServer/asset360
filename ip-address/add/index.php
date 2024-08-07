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
                <label for="vpn_ip_address" class="form-label">VPN IP Address</label>
                <input type="text" class="form-control" id="vpn_ip_address" name="vpn_ip_address">
            </div>
            <div class="col">
                <label for="assigned_asset_tag_no" class="form-label">Asset Tag Number</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="assigned_asset_tag_no" name="assigned_asset_tag_no" value="<?php echo $asset_tag_no; ?>">
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#assetModal">
                        <i class="bi bi-search"></i>
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


<!-- Modal -->
<div class="modal fade" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assetModalLabel">Select Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search Asset" id="assetSearchInput">
                    <button class="btn btn-primary" type="button" id="assetSearchBtn">Search</button>
                </div>
                <div class="list-group list-group-flush" id="assetList">
                    <!-- Assets will be dynamically loaded here -->
                </div>
            </div>
            <div class="modal-footer">
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
document.addEventListener('DOMContentLoaded', function () {
    var assetSearchInput = document.getElementById('assetSearchInput');
    var assetList = document.getElementById('assetList');

    document.getElementById('assetSearchBtn').addEventListener('click', function () {
        var searchValue = assetSearchInput.value.trim();
        fetchAssets(searchValue);
    });

    function fetchAssets(searchValue) {
        // Fetch assets from the server based on the search value
        fetch('<?php echo BASE_URL; ?>/api/search_assets.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ keyword: searchValue }),
        })
        .then(response => response.json())
        .then(data => {
            renderAssets(data);
        })
        .catch(error => {
            console.error('Error fetching assets:', error);
        });
    }

    function renderAssets(assets) {
        // Clear existing assets
        assetList.innerHTML = '';

        if (assets.length > 0) {
            // Create table element
            var table = document.createElement('table');
            table.classList.add('table', 'table-striped');

            // Create table header row
            var thead = document.createElement('thead');
            var headerRow = document.createElement('tr');

            // Create table headers
            var selectHeader = document.createElement('th');
            selectHeader.textContent = 'Select';
            headerRow.appendChild(selectHeader);

            var nameHeader = document.createElement('th');
            nameHeader.textContent = 'Asset Name';
            headerRow.appendChild(nameHeader);

            var tagHeader = document.createElement('th');
            tagHeader.textContent = 'Asset Tag';
            headerRow.appendChild(tagHeader);

            thead.appendChild(headerRow);
            table.appendChild(thead);

            // Create table body
            var tbody = document.createElement('tbody');

            assets.forEach(asset => {
                // Create table row
                var row = document.createElement('tr');

                // Create select button cell
                var selectCell = document.createElement('td');
                var selectButton = document.createElement('button');
                selectButton.textContent = 'Select';
                selectButton.classList.add('badge', 'text-bg-success', 'select-button');
                selectButton.setAttribute('data-asset-id', asset.asset_tag_no);
                selectCell.appendChild(selectButton);
                row.appendChild(selectCell);

                // Create asset name cell
                var nameCell = document.createElement('td');
                nameCell.textContent = asset.asset_name;
                row.appendChild(nameCell);

                // Create asset tag cell
                var tagCell = document.createElement('td');
                tagCell.textContent = asset.asset_tag_no;
                row.appendChild(tagCell);

                // Append row to table body
                tbody.appendChild(row);

                // Attach event listener to the select button
                selectButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    var selectedAssetTagNo = selectButton.getAttribute('data-asset-id');
                    document.getElementById('assigned_asset_tag_no').value = selectedAssetTagNo;
                    $('#assetModal').modal('hide');
                });
            });

            table.appendChild(tbody);
            assetList.appendChild(table);
        } else {
            var message = document.createElement('p');
            message.className = 'text-muted';
            message.textContent = 'No assets found.';
            assetList.appendChild(message);
        }
    }
});

</script>







</body>
</html>