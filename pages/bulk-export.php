<?php
date_default_timezone_set('America/Denver');
require_once "../app/database/connection.php";
// require_once "app/functions/add_app.php";
require_once "../path.php";
require_once "convert-pdf.php";
session_start();

$files = glob("../app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}
logoutUser($conn);
if(isLoggedIn() == false) {
    header('location:' . BASE_URL . '/login.php');
}



// 




// 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo time(); ?>">

    <title>Bulk Export | Asset360</title>
</head>
<body>
    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

    <!-- main-container -->
        <div class="container" style="padding: 0 75px 0 75px;">
            <h2 class="mt-4">
                Bulk Export Asset Inventory
            </h2>
            <!-- <button class="btn btn-primary" onclick="exportToCSV()">Export to CSV</button> -->
            <button class="float-end export-button btn btn-success" style="margin-top: -40px;" id="generatePdfButton">Export <i class="bi bi-file-earmark-arrow-down-fill"></i></button>


            <hr>

            <table class="table">
    <thead>
        <tr>
            <th scope="col">Tag No</th>
            <th scope="col">Asset Name</th>
            <th scope="col">IP Address</th>
            <th scope="col">Type</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // Pagination variables
    $limit = 10; // Number of entries per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    
    $sql = "SELECT assets.*, ip_address.ip_address AS ip_address
            FROM assets
            LEFT JOIN ip_address ON assets.asset_tag_no = ip_address.assigned_asset_tag_no
            ORDER BY assets.created_at ASC
            LIMIT $limit OFFSET $offset";
    $result = mysqli_query($conn, $sql);
    if($result) {
        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id                     = $row['asset_id']; 
                $asset_name             = $row['asset_name']; 
                $asset_tag_no           = $row['asset_tag_no']; 
                $status                 = $row['status']; 
                $maintenance_schedule   = $row['maintenance_schedule'];
                $audit_schedule         = $row['audit_schedule']; 
                $asset_type             = $row['asset_type']; 
                $created_at             = $row['created_at']; 
                $ip_address             = $row['ip_address']; 

                // Format maintenance schedule if not null
                $f_maintenance_schedule = !empty($maintenance_schedule) ? date_format(date_create($maintenance_schedule), 'M d, Y') : '-';

                // Format audit schedule if not null
                $f_audit_schedule = !empty($audit_schedule) ? date_format(date_create($audit_schedule), 'M d, Y') : '-';
?>
<tr>
    <th scope="row"><?php echo $asset_tag_no; ?></th>
    <td><?php echo $asset_name ? $asset_name : '-'; ?></td>
    <td><?php echo $ip_address ? $ip_address : '-'; ?></td>
    <td><?php echo $asset_type ? $asset_type : '-'; ?></td>
    <td><?php echo $status ? $status : '-'; ?></td>
    <td style="font-size: 20px;"><a href="" data-bs-toggle="offcanvas" data-bs-target="#view_asset<?php echo $id; ?>" aria-controls="offcanvasRight"><i class="bi bi-eye text-success"></i></a> &nbsp; <a href="update-app.php?updateid=<?php echo $id; ?>"><i class="bi bi-pencil-square" style="color:#005382;"></a></i> &nbsp; <a href="bulk-export.php?deleteid=<?php echo $id; ?>" class="delete"><i class="bi bi-trash" style="color:#941515;"></i></a></td>
</tr>


<div class="offcanvas offcanvas-end" tabindex="-1" id="view_asset<?php echo $id; ?>" aria-labelledby="offcanvasRightLabel" style="width: 40%;">
  <div class="offcanvas-header d-block">
    <!-- PHP for OFF CANVAS -->
        <?php
            $off_sql = "SELECT assets.*, ip_address.ip_address AS ip_address
            FROM assets
            LEFT JOIN ip_address ON assets.asset_tag_no = ip_address.assigned_asset_tag_no
            WHERE asset_id = $id
            ORDER BY assets.created_at ASC
            LIMIT $limit OFFSET $offset";
            $off_result = mysqli_query($conn, $off_sql);
            if($off_result) {
            $num_rows = mysqli_num_rows($off_result);
            if($num_rows > 0) {
                while ($off_row = mysqli_fetch_assoc($off_result)) {
                    $off_id                     = $off_row['asset_id']; 
                    $off_asset_name             = $off_row['asset_name']; 
                    $off_asset_tag_no           = $off_row['asset_tag_no'];
                    $off_manufacturer_name      = $off_row['manufacturer_name'];
                    $off_model                  = $off_row['model'];
                    $off_model_no               = $off_row['model_no'];
                    $off_acquisition_date       = $off_row['acquisition_date'];
                    $off_end_of_life_date       = $off_row['end_of_life_date'];
                    $off_location               = $off_row['location'];
                    $off_custodian              = $off_row['custodian'];
                    $off_serial_number          = $off_row['serial_number'];
                    $off_notes                  = $off_row['notes']; 
                    $off_status                 = $off_row['status']; 
                    $off_maintenance_schedule   = $off_row['maintenance_schedule'];
                    $off_audit_schedule         = $off_row['audit_schedule']; 
                    $off_asset_type             = $off_row['asset_type']; 
                    $off_created_at             = $off_row['created_at'];
                    $off_updated_at             = $off_row['updated_at'];
                    $off_ip_address             = $off_row['ip_address']; 
                }}}
        ?>
    <!-- end PHP for OFF CANVAS -->
    <div class="top-header d-flex">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">View Asset <?php echo $off_asset_tag_no; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <p class="text-muted" style="font-size: 12px;">
        <span class="pe-3">
            Last updated: 
            <?php 
            $formatted_off_updated_at = date("M d, Y", strtotime($off_updated_at));
            echo $formatted_off_updated_at ? $formatted_off_updated_at : '-'; 
            ?>
        </span>
        <span>
            Created: 
            <?php 
            $formatted_off_created_at = date("M d, Y", strtotime($off_created_at));
            echo $formatted_off_created_at ? $formatted_off_created_at : '-'; 
            ?>
        </span>
    </p>
    <div class="hr" style="border-bottom: 1px solid rgb(217,222,226);"></div>
  </div>
  <div class="offcanvas-body">
    
  <div class="row">
    <div class="col-sm-8">
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Asset Tag No
            </span>
            <span class="float-end">
                <?php echo $off_asset_tag_no ? $off_asset_tag_no : '-'; ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Asset Name
            </span>
            <span class="float-end">
                <?php echo $off_asset_name ? $off_asset_name : '-'; ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Type
            </span>
            <span class="float-end">
                <?php echo $off_asset_type ? $off_asset_type : '-'; ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Serial Number
            </span>
            <span class="float-end">
                <?php echo $off_serial_number ? $off_serial_number : '-'; ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Manufacturer Name
            </span>
            <span class="float-end">
                <?php echo $off_manufacturer_name ? $off_manufacturer_name : '-'; ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Model
            </span>
            <span class="float-end">
                <?php echo $off_model ? $off_model : '-'; ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Model Number
            </span>
            <span class="float-end">
                <?php echo $off_model_no ? $off_model_no : '-'; ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                IP Address
            </span>
            <span class="float-end">
                <?php echo $off_ip_address ? $off_ip_address : '-'; ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Location
            </span>
            <span class="float-end">
                <?php echo $off_location ? $off_location : '-'; ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Custodian
            </span>
            <span class="float-end">
                <?php echo $off_custodian ? $off_custodian : '-'; ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Acquisition Date
            </span>
            <span class="float-end">
                <?php 
                $formatted_off_acquisition_date = date("M d, Y", strtotime($off_acquisition_date));
                echo $formatted_off_acquisition_date ? $formatted_off_acquisition_date : '-'; 
                ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                End of Life Date
            </span>
            <span class="float-end">
                <?php 
                $formatted_off_end_of_life_date = date("M d, Y", strtotime($off_end_of_life_date));
                echo $formatted_off_end_of_life_date ? $formatted_off_end_of_life_date : '-'; 
                ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Next Scheduled Audit
            </span>
            <span class="float-end">
                <?php 
                $formatted_off_audit_schedule = date("M d, Y", strtotime($off_audit_schedule));
                echo $formatted_off_audit_schedule ? $formatted_off_audit_schedule : '-'; 
                ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="float-start fw-bold">
                Last Completed Maintenance
            </span>
            <span class="float-end">
            <!-- PHP CODE EVENT -->
                <?php
                $e_sql = "SELECT *
                FROM event_log
                WHERE asset_tag_no = '$off_asset_tag_no' AND event_type = 2
                LIMIT 1";
                $e_result = mysqli_query($conn, $e_sql);
                if ($e_result) {
                    $num_rows = mysqli_num_rows($e_result);
                    if ($num_rows > 0) {
                        while ($e_row = mysqli_fetch_assoc($e_result)) {
                            $e_id               = $e_row['event_id'];
                            $e_asset_tag_no     = $e_row['asset_tag_no'];
                            $e_event_type       = $e_row['event_type'];
                            $e_date_completed   = $e_row['completed_date'];
                            $e_status           = $e_row['status'];
                            $e_completed_by     = $e_row['completed_by'];
                            $e_notes            = $e_row['notes'];
                            $e_created_at       = $e_row['created_at'];
                            $e_updated_at       = $e_row['updated_at'];
                        }
                    } else {
                        // No records found, set default values or handle accordingly
                        $e_id               = null;
                        $e_asset_tag_no     = null;
                        $e_event_type       = null;
                        $e_date_completed   = null;
                        $e_status           = null;
                        $e_completed_by     = null;
                        $e_notes            = null;
                        $e_created_at       = null;
                        $e_updated_at       = null;
                    }
                } else {
                    // Error executing the query
                    // Handle the error
                }
                ?>
            <!-- end PHP CODE EVENT -->
            <?php echo isset($e_date_completed) ? $e_date_completed : '-'; ?>
            </span>
        </li>
        <li class="list-group-item">
            <span class="fw-bold">
                Notes
            </span>
            <span class="">
                <?php echo $off_notes ? $off_notes : '-'; ?>
            </span>
        </li>
    </ul>
</div>
<div class="col-sm-3 ms-3" style="">
    <button class="badge text-bg-primary" data-bs-toggle="modal" data-bs-target="#maintenanceModal">Maintenance</button>

    <span class="d-flex justify-content-center align-items-center mx-auto mt-2" style="font-size: 75px; border: 2px solid rgb(217,222,226); width: 150px; height: 150px; border-radius: 10px;">
        <?php if($off_asset_type == 'Server') { ?>
            <i class="bi bi-hdd-stack"></i>
        <?php } else if($off_asset_type == 'Computer') { ?>
            <i class="bi bi-pc-display-horizontal"></i>
        <?php } else if($off_asset_type == 'Network Device') { ?>
            <i class="bi bi-diagram-2"></i>
        <?php } else if($off_asset_type == 'Mobile Device') { ?>     
            <i class="bi bi-phone"></i>
        <?php } else if($off_asset_type == 'Storage Device') { ?>
            <i class="bi bi-device-ssd"></i>
        <?php } else if($off_asset_type == 'IOT Device') { ?>
            <i class="bi bi-tv"></i> 
        <?php } else if($off_asset_type == 'Peripheral') { ?>
            <i class="bi bi-printer"></i>
        <?php } else { ?>
            <i class="bi bi-exclamation-octagon"></i>
        <?php } ?>
    </span>
    <span>
        <p class="fw-bold">
            Managed by
        </p>
        <p style="font-size: 12px; margin-top: -10px;">
            <?php echo $off_custodian ? $off_custodian : '-'; ?>
        </p>
    </span>
</div>
</div>
    

  </div>
</div>

<!-- Maintenance Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="maintenanceModalLabel">Maintenance Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body - Your form goes here -->
                <div class="modal-body">
                    <form method="POST">
                        <div class="col">
                            <label for="asset_tag_no" class="form-label">Asset Tag Number</label>
                            <input type="text" class="form-control" id="asset_tag_no" name="asset_tag_no" value="<?php echo $off_asset_tag_no;?>" readonly>
                        </div>
                        <div class="col">
                            <label for="event_type" class="form-label">Event Type</label>
                            <input type="text" class="form-control" id="event_type" name="event_type" value="2">
                        </div>
                        <div class="col">
                            <label for="completed_by" class="form-label">Completed By</label>
                            <input type="text" class="form-control" id="completed_by" name="<?php echo $_SESSION['fname'] . ' ' . $_SESSION['lname'];?>">
                        </div>
                        <div class="col">
                            <?php $cdate = date("Y-m-d"); ?>
                            <label for="date_completed" class="form-label">Date Completed</label>
                            <input type="date" class="form-control" id="date_completed" name="date_completed" value="<?php echo $cdate; ?>">
                        </div>
                        <div class="col">
                            <label for="status" class="form-label">Status</label>
                            <input type="text" class="form-control" id="status" name="status" value="1">
                        </div>
                        <!-- Add more form fields as needed -->
                        <button type="submit" name="add-maintenance" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- end Maintenance Modal -->




<?php
            }
        }
    }
?>

    </tbody>
</table>

        <br>
        <?php
            // Pagination links
            $sql = "SELECT COUNT(*) as total FROM assets";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $total_pages = ceil($row["total"] / $limit);

                echo '<ul class="pagination justify-content-center">';
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = ($page == $i) ? "active" : "";
                    echo "<li class='page-item {$active}'><a class='page-link' href='?page={$i}'>{$i}</a></li>";
                }
                echo '</ul>';
        ?>


        </div>
    <!-- END main-container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#generatePdfButton').click(function() {
        // Redirect to the same page with a query parameter indicating PDF generation
        window.location.href = 'bulk-export.php?generatePdf=1';
    });
});
</script>





    <!-- <script src="<?php //echo ROOT_PATH; ?>/assets/js/export_pdf.js"></script> -->
</body>
</html>