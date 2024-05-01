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

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>View Asset | Asset360</title>

    <!-- Styles -->
        <style>
            .application-details {
                max-width: 80%;
                padding: 20px;
                margin: 20px auto;
            }

            .detail-label {
                font-weight: bold;
            }

            .detail-value {
                margin-bottom: 10px;
            }
            .card-container {
                display: flex;
                justify-content: space-between; 
            }

            .card {
                width: calc(33.33% - 20px); 
                margin-bottom: 20px; 
            }

            @media (max-width: 992px) {
                .card-container {
                    flex-wrap: wrap;
                }
                .card {
                    width: 100%; 
                }
            }
            .icon_rotate {
                /* filter: progid: DXImageTransform.Microsoft.BasicImage(rotation=1); */
                -webkit-transform: rotate(-45deg);
                -moz-transform: rotate(-45deg);
                -ms-transform: rotate(-45deg);
                -o-transform: rotate(-45deg);
                transform: rotate(-45deg);
                display: inline-block;
            }
            .scrollable-table-container {
                overflow-y: auto; 
                height: calc(100vh - 375px);
            }
            .sticky-header th {
              position: sticky;
              top: 0;
              background-color: #fff;
              z-index: 1;
            }

            .sticky-header th::after {
              content: '';
              position: absolute;
              left: 0;
              bottom: 0;
              width: 100%;
              border-bottom: 2px solid rgb(217, 222, 226);
            }
        </style>
    <!-- end Styles -->

</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>

<div class="container-fluid" style="margin-top: 60px;">
    <div class="application-details">
        <!-- php code for getting asset details -->
            <?php
            $id = $_GET['id'];
            $off_sql = "SELECT assets.*, ip_address.ip_address AS ip_address
            FROM assets
            LEFT JOIN ip_address ON assets.asset_tag_no = ip_address.assigned_asset_tag_no
            WHERE asset_id = $id
            ORDER BY assets.asset_created ASC";
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
                    $off_created_at             = $off_row['asset_created'];
                    $off_updated_at             = $off_row['asset_updated'];
                    $off_ip_address             = $off_row['ip_address']; 
                }
            // }}
            ?>
        <!-- end php code for getting asset details -->

                <h2>
                    <?php echo $off_asset_name; ?>
                    <span class="ps-3" style="font-size: 14px;">
                        <?php if($off_status == 'Sub Let'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-primary"></i> &nbsp; <?php echo $off_status; ?></span>
                        <?php } else if($off_status == 'In Storage'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-info"></i> &nbsp; <?php echo $off_status; ?></span>
                        <?php } else if($off_status == 'In Use'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-success"></i> &nbsp; <?php echo $off_status; ?></span>
                        <?php } else if($off_status == 'In Repair'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-danger"></i> &nbsp; <?php echo $off_status; ?></span>
                        <?php } else if($off_status == 'Unknown' || $off_status == 'Sold' || $off_status == 'Disposed'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-secondary"></i> &nbsp; <?php echo $off_status; ?></span>
                        <?php } ?>
                    </span>
                    <span class="float-end d-flex">
                        <!-- JIRA BUTTONS -->
                            <a class="badge text-bg-primary text-decoration-none me-2" style="font-size: 14px; cursor: pointer;" id="createTicketButton" data-bs-toggle="modal" data-bs-target="#auditModal">
                                <i class="bi bi-shield-fill-check"></i> &nbsp;Perform Audit
                            </a>
                            <a class="badge text-bg-primary text-decoration-none me-2" style="font-size: 14px; cursor: pointer;" id="createTicketButton" data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                                <i class="bi bi-ticket-fill icon_rotate"></i> &nbsp;Create a Ticket
                            </a>
                            
                            <!-- AUDIT modal -->
                                <div class="modal fade" id="auditModal" tabindex="-1" aria-labelledby="auditModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="auditModalLabel">Perform Audit</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="auditModalForm">
                                                    <input type="hidden" class="form-control" id="asset_tag" name="asset_tag" value="<?php echo '[' . $off_asset_tag_no. '] '; ?>">
                                                    <div class="mb-3">
                                                        <label for="summary" class="form-label" style="font-size: 14px;">Summary Title:</label>
                                                        <input type="text" class="form-control" id="summary" name="summary" required>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col">
                                                            <label class="form-label" for="notes" style="font-size: 14px;">Notes</label>
                                                            <textarea class="form-control" id="notes" name="notes" rows="5"></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- Add more fields as needed -->
                                                    <button type="submit" class="btn btn-primary">Create Issue</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- End Modal for AUDIT -->

                            <!-- MAINTENANCE modal -->
                                <div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="maintenanceModalLabel">Create a Maintenance Ticket</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="maintenanceModalForm">
                                                    <input type="hidden" class="form-control" id="m_asset_tag" name="m_asset_tag" value="<?php echo '[' . $off_asset_tag_no. '] '; ?>">
                                                    <div class="mb-3">
                                                        <label for="m_summary" class="form-label" style="font-size: 14px;">Summary Title:</label>
                                                        <input type="text" class="form-control" id="m_summary" name="m_summary" required>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col">
                                                            <label class="form-label" for="m_notes" style="font-size: 14px;">Notes</label>
                                                            <textarea class="form-control" id="m_notes" name="m_notes" rows="5"></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- Add more fields as needed -->
                                                    <button type="submit" class="btn btn-primary">Create Issue</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- End Modal for MAINTENANCE -->

                        <div class="vertical-line ms-2 me-2" style="border-left: 1px solid #999; height:25px;"></div>
                        <a class="badge text-bg-success text-decoration-none me-1" style="font-size: 14px;" href="update-app.php?updateid=<?php echo $id; ?>">Edit</a>
                        <a class="badge text-bg-danger text-decoration-none" style="font-size: 14px;" href="open-app.php?deleteid=<?php echo $id; ?>">Delete</a>
                    </span>
                </h2>

                <?php 
                    $off_updated_at = strtotime($off_updated_at);
                    $updated_at_formatted = date('M j, Y', $off_updated_at);
                    $off_created_at = strtotime($off_created_at);
                    $created_at_formatted = date('M j, Y', $off_created_at);
                ?>
                <p class="text-muted" style="font-size: 12px;">
                    <span class="pe-3">
                        Last updated: <?php echo $updated_at_formatted; ?>
                    </span>
                    <span>
                        Created: <?php echo $created_at_formatted; ?>
                    </span>
                </p>




                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane" type="button" role="tab" aria-controls="details-tab-pane" aria-selected="true">Details</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes-tab-pane" type="button" role="tab" aria-controls="notes-tab-pane" aria-selected="false">Notes</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events-tab-pane" type="button" role="tab" aria-controls="events-tab-pane" aria-selected="false">Event Log</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="jira-tab" data-bs-toggle="tab" data-bs-target="#jira-tab-pane" type="button" role="tab" aria-controls="jira-tab-pane" aria-selected="false">Jira Tickets</button>
                  </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Details -->
                        <div class="tab-pane fade show active" id="details-tab-pane" role="tabpanel" aria-labelledby="details-tab" tabindex="0">

                            <div class="mt-4"></div>
                            <?php if($off_asset_type == 'Server') { ?>
                                <h4><i class="bi bi-hdd-stack"></i> Server Details</h4>
                            <?php } else if($off_asset_type == 'Computer') { ?>
                                <h4><i class="bi bi-pc-display-horizontal"></i> Computer Details</h4>
                            <?php } else if($off_asset_type == 'Network Device') { ?>
                                <h4><i class="bi bi-diagram-2"></i> Network Device Details</h4>
                            <?php } else if($off_asset_type == 'Mobile Device') { ?>
                                <h4><i class="bi bi-phone"></i> Mobile Device Details</h4>
                            <?php } else if($off_asset_type == 'Storage Device') { ?>
                                <h4><i class="bi bi-device-ssd"></i> Storage Device Details</h4>
                            <?php } else if($off_asset_type == 'IOT Device') { ?>
                                <h4><i class="bi bi-tv"></i> IOT Device Details</h4>
                            <?php } else if($off_asset_type == 'Peripheral') { ?>
                                <h4><i class="bi bi-printer"></i> Peripheral Details</h4>
                            <?php } ?>

                            <hr>

                            <div class="con d-flex">
                                <div class=" w-50">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">Asset Tag Number</div>
                                            </div>
                                            <span class=""><?php echo $off_asset_tag_no ? $off_asset_tag_no : '--'; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">Asset Type</div>
                                            </div>
                                            <span class=""><?php echo $off_asset_type ? $off_asset_type : '--'; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">Serial Number</div>
                                            </div>
                                            <span class=""><?php echo $off_serial_number ? $off_serial_number : '--'; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">Model</div>
                                            </div>
                                            <span class=""><?php echo $off_model ? $off_model : '--'; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">Model Number</div>
                                            </div>
                                            <span class=""><?php echo $off_model_no ? $off_model_no : '--'; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">Manufacturer Name</div>
                                            </div>
                                            <span class=""><?php echo $off_manufacturer_name ? $off_manufacturer_name : '--'; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">Location</div>
                                            </div>
                                            <span class=""><?php echo $off_location ? $off_location : '--'; ?></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="ms-3 w-50">
                                    <?php
                                        
                                        $off_acquisition_date = strtotime($off_acquisition_date);
                                        $acq_date_formatted = date('M j, Y', $off_acquisition_date);
                                        $off_end_of_life_date = strtotime($off_end_of_life_date);
                                        $end_of_life_date_formatted = date('M j, Y', $off_end_of_life_date);
                                        $off_audit_schedule = strtotime($off_audit_schedule);
                                        $audit_schedule_formatted = date('M j, Y', $off_audit_schedule);

                                        $id = $_GET['id'];
                                        $newsql = "SELECT *
                                        FROM event_log
                                        JOIN assets ON event_log.asset_tag_no = assets.asset_tag_no
                                        WHERE event_log.event_type = 'Maintenance'
                                        AND assets.asset_id = '$off_id'
                                        ORDER BY event_log.date_reviewed DESC
                                        LIMIT 1";
                                        $newresult = mysqli_query($conn, $newsql);
                                        if (mysqli_num_rows($newresult) > 0) {
                                            while ($newrow = mysqli_fetch_assoc($newresult)) { 
                                                $reviewed = $newrow['date_reviewed'];

                                                $reviewed_formatted = !empty($reviewed) ? date_format(date_create($reviewed), 'M d, Y') : '--';  

                                            }
                                        }
                                    ?>

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">Acquisition Date</div>
                                            </div>
                                            <span class=""><?php echo $acq_date_formatted ? $acq_date_formatted : '--'; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">End of Life Date</div>
                                            </div>
                                            <span class=""><?php echo $end_of_life_date_formatted ? $end_of_life_date_formatted : '--'; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">Asset Custodian</div>
                                            </div>
                                            <span class=""><?php echo $off_custodian ? $off_custodian : '--'; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">Next Audit</div>
                                            </div>
                                            <span class=""><?php echo $audit_schedule_formatted ? $audit_schedule_formatted : '--'; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 30%;">
                                                <div class="fw-bold">Last Maintenance</div>
                                            </div>
                                            <span class=""><?php echo $reviewed_formatted ? $reviewed_formatted : '--'; ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                    


                        </div>
                    <!-- end Details -->

                    <!-- Notes -->
                        <div class="tab-pane fade" id="notes-tab-pane" role="tabpanel" aria-labelledby="notes-tab" tabindex="0">
                    
                            <div class="mt-4"></div>
                            <h4><i class="bi bi-file-earmark-text-fill"></i> Notes</h4>
                            <hr>

                            <div id="accordion">
                                <?php
                                // Check if $notes is not empty
                                if (!empty($off_notes)) {
                                    // Match all <h5> tags and their content
                                    preg_match_all('/<h5>(.*?)<\/h5>(.*?)(?=<h5>|$)/s', $off_notes, $matches, PREG_SET_ORDER);
                                
                                    // Loop through each matched note
                                    foreach ($matches as $index => $match) {
                                        // Extract title and content
                                        $title = $match[1];
                                        $content = $match[2];
                                    
                                        // Display accordion item
                                        ?>
                                        <div class="accordion-item">

                                            <h5 class="accordion-header" id="heading<?= $index ?>">
                                                <button class="accordion-button collapsed fw-bold" id="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>">
                                                    <?= $title ?> <!-- Use text within <h5> tags as button/title -->
                                                    <i id="chev" class="bi bi-chevron-down" style="position: absolute; top: 50%; transform: translateY(-50%); right: 10px;"></i>
                                                </button>
                                            </h5>
                                            <div id="collapse<?= $index ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index ?>" data-bs-parent="#accordion">
                                                <div class="accordion-body">
                                                    <?= $content ?> <!-- Output the content under the <h5> tag as accordion body -->
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    // Display message if $notes is empty
                                    ?>
                                    <div class="accordion-item">
                                        <h5 class="accordion-header">
                                            <span class="text-warning">No notes found.</span>
                                        </h5>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>


                        </div>
                    <!-- end Notes -->

                    <!-- Events -->
                        <!-- <div class="tab-pane fade" id="events-tab-pane" role="tabpanel" aria-labelledby="events-tab" tabindex="0">
                            <div class="mt-4"></div>
                            <h4><i class="bi bi-tools"></i> Latest Events</h4>
                            <hr>

                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">Tag No</th>
                                    <th scope="col">Event Type</th>
                                    <th scope="col">Performed</th>
                                    <th scope="col">Performed By</th>
                                    <th scope="col">Reviewed</th>
                                    <th scope="col">Reviewed By</th>
                                    <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $limit = 10; 
                                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                        $offset = ($page - 1) * $limit;

                                        $esql = "SELECT * FROM event_log WHERE asset_tag_no = '$off_asset_tag_no' ORDER BY event_created DESC LIMIT $limit OFFSET $offset";
                                        $eresult = mysqli_query($conn, $esql);
                                        if($eresult) {
                                            $enum_rows = mysqli_num_rows($eresult);
                                            if($enum_rows > 0) {
                                                while ($erow = mysqli_fetch_assoc($eresult)) {
                                                    $id                     = $erow['event_id'];
                                                    $idno                   = $erow['idno'];
                                                    $status                 = $erow['status'];
                                                    $date_reviewed          = $erow['date_reviewed'];
                                                    $date_performed         = $erow['date_performed'];
                                                    $asset_tag_no           = $erow['asset_tag_no'];
                                                    $performed_by           = $erow['performed_by'];
                                                    $reviewed_by            = $erow['reviewed_by'];
                                                    $event_type             = $erow['event_type'];
                                                    $notes                  = $erow['notes'];
                                                    $event_created          = $erow['event_created'];    
                                                    $event_updated          = $erow['event_updated'];                 

                                                    
                                                    $f_date_reviewed = !empty($date_reviewed) ? date_format(date_create($date_reviewed), 'M d, Y') : '--';           
                                                    $f_date_performed = !empty($date_performed) ? date_format(date_create($date_performed), 'M d, Y') : '--';       

                                                    
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $asset_tag_no; ?></th>
                                        <td><?php echo $event_type ? $event_type : '--'; ?></td>
                                        <td><?php echo $f_date_performed ? $f_date_performed : '--'; ?></td>
                                        <td><?php echo $performed_by ? $performed_by : '--'; ?></td>
                                        <td><?php echo $f_date_reviewed ? $f_date_reviewed : '--'; ?></td>
                                        <td><?php echo $reviewed_by ? $reviewed_by : '--'; ?></td>
                                        <?php if($status == "Awaiting Approval") { ?>
                                            <td><span class="badge text-bg-primary"><?php echo $status; ?></span></td>
                                        <?php } else if($status == "Completed") { ?>
                                            <td><span class="badge text-bg-success"><?php echo $status; ?></span></td>
                                        <?php } else if($status == "Rejected") { ?>
                                            <td><span class="badge text-bg-danger"><?php echo $status; ?></span></td>
                                        <?php } else if($status == "Rescheduled") { ?>
                                            <td><span class="badge text-bg-warning"><?php echo $status; ?></span></td>
                                        <?php } else { ?>
                                            <td>--</td>
                                        <?php } ?>
                                        
                                    </tr>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <br>
                            <?php
                                
                                $sql = "SELECT COUNT(*) as total FROM event_log WHERE asset_tag_no = '$off_asset_tag_no'";
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


                        </div> -->
                    <!-- end Events -->

                    <!-- Jira -->
                        <div class="tab-pane fade" id="jira-tab-pane" role="tabpanel" aria-labelledby="jira-tab" tabindex="0">

                                
                            <div class="mt-4"></div>
                            <h4><i class="bi bi-ticket-fill icon_rotate"></i> Latest Issues</h4>
                            <hr>

                            <div class="scrollable-table-container">
                                <table class="table">
                                  <thead class="sticky-header">
                                    <tr>
                                      <th scope="col">Issue Key</th>
                                      <th scope="col">Issue Type</th>
                                      <th scope="col">Summary</th>
                                      <th scope="col">Link</th>
                                    </tr>
                                  </thead>
                                  <tbody id="jiraTableBody">
                                    <!-- Table rows will be dynamically added here -->
                                  </tbody>
                                </table>
                            </div>

                        <!-- get audit issues script -->
                            <script>
                                var assetTag = "<?php echo $off_asset_tag_no; ?>";
                                fetch('<?php echo BASE_URL; ?>/api/get_jira_data.php?asset_tag=' + assetTag)
                                  .then(response => {
                                    if (!response.ok) {
                                      throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                  })
                                  .then(data => {
                                    document.getElementById("jiraTableBody").innerHTML = "";
                                    data.issues.forEach(issue => {
                                      var newRow = document.createElement("tr");
                                      newRow.innerHTML = `<td>${issue.key}</td><td>${issue.fields.summary}</td><td>${issue.fields.issuetype.name}</td><td><a href="https://garrett-morgan.atlassian.net/browse/${issue.key}" target="_blank" class="badge text-bg-primary text-decoration-none" style="font-size: 14px;">Visit</a></td>`;
                                      document.getElementById("jiraTableBody").appendChild(newRow);
                                    });
                                  })
                                  .catch(error => {
                                    console.error('Fetch Error:', error);
                                    // Handle the error gracefully, e.g., display a message to the user
                                    // Since the response is not JSON, you might want to inform the user accordingly
                                  });

                            </script>

                        <!-- end get audit issues script -->


                        </div>
                    <!-- end Jira -->
                </div>

                
                <!-- __________ -->
                
                

                <!-- Maintenance Modal -->
                    <div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="maintenanceModalLabel">Maintenance Request</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <!-- Modal Body - Your form goes here -->
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" class="form-control" id="event_type" name="event_type" value="Maintenance">
                                        <!-- <input type="hidden" class="form-control" id="reviewed_by" name="reviewed_by" value="<?php //echo $_SESSION['fname'] . ' ' . $_SESSION['lname']; ?>"> -->
                                        <input type="hidden" class="form-control" id="asset_tag_no" name="asset_tag_no" value="<?php echo $off_asset_tag_no;?>">
                                        <input type="hidden" class="form-control" id="status" name="status" value="Awaiting Approval">
                                        <input type="hidden" class="form-control" id="performed_by" name="performed_by" value="System Administrator">
                                        
                                        <div class="row">
                                            <div class="col">
                                                <label for="asset_tag_no" class="form-label fw-bold">Asset Tag Number</label><br>
                                                <?php echo $off_asset_tag_no; ?>
                                            </div>
                                            <div class="col">
                                                <label for="performed_by" class="form-label fw-bold">Performed By</label><br>
                                                <?php echo 'System Administrator'; ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col">
                                            <?php $cdate = date("Y-m-d"); ?>
                                            <label for="date_requested" class="form-label">Date Performed</label>
                                            <input type="date" class="form-control" id="date_performed" name="date_performed" value="<?php echo $cdate; ?>">
                                        </div>
                                        <div class="row pt-3">
                                            <div class="col">
                                                <label class="form-label" for="notes">Notes</label>
                                                <textarea class="form-control" name="notes" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <!-- Add more form fields as needed -->
                                        <button type="submit" name="add-event" class="btn btn-primary mt-3">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- end Maintenance Modal -->

                <!-- Audit Modal -->
                    <div class="modal fade" id="auditModal" tabindex="-1" aria-labelledby="auditModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="auditModalLabel">Perform an Audit</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <!-- Modal Body - Your form goes here -->
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" class="form-control" id="event_type" name="event_type" value="Audit">
                                        <!-- <input type="hidden" class="form-control" id="reviewed_by" name="reviewed_by" value="<?php //echo $_SESSION['fname'] . ' ' . $_SESSION['lname']; ?>"> -->
                                        <input type="hidden" class="form-control" id="asset_tag_no" name="asset_tag_no" value="<?php echo $off_asset_tag_no;?>">
                                        <input type="hidden" class="form-control" id="status" name="status" value="Awaiting Approval">
                                        <input type="hidden" class="form-control" id="performed_by" name="performed_by" value="Compliance Analyst">

                                        <div class="row">
                                            <div class="col">
                                                <label for="asset_tag_no" class="form-label fw-bold">Asset Tag Number</label><br>
                                                <?php echo $off_asset_tag_no; ?>
                                            </div>
                                            <div class="col">
                                                <label for="performed_by" class="form-label fw-bold">Performed By</label><br>
                                                <?php echo 'Compliance Analyst'; ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col">
                                            <?php $cdate = date("Y-m-d"); ?>
                                            <label for="date_performed" class="form-label">Date Performed</label>
                                            <input type="date" class="form-control" id="date_performed" name="date_performed" value="<?php echo $cdate; ?>">
                                        </div>
                                        <div class="row pt-3">
                                            <div class="col">
                                                <label class="form-label" for="notes">Notes</label>
                                                <textarea class="form-control" name="notes" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <!-- Add more form fields as needed -->
                                        <button type="submit" name="add-event" class="btn btn-primary mt-3">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- end Audit Modal -->





                <!-- ___________ -->
                

            <?php }
        } ?>
    </div>
</div>


   



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


<!-- audit script -->
    <script>
        document.getElementById('auditModalForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            var asset_tag = document.getElementById('asset_tag').value;
            var summary = document.getElementById('summary').value;
            var notes = document.getElementById('notes').value;
            var combinedSummary = asset_tag + summary;
            var auditIssueData = {
                "fields": {
                    "project": {
                        "key": "SG"
                    },
                    "summary": combinedSummary,
                    "description": {
                        "type": "doc",
                        "version": 1,
                        "content": [
                            {
                                "type": "paragraph",
                                "content": [
                                    {
                                        "type": "text",
                                        "text": notes
                                    }
                                ]
                            }
                        ]
                    },
                    "issuetype": {
                        "id": "10029"
                    }
                }
            };

            // Convert issueData to FormData object
            var formData = new FormData();
            formData.append('auditIssueData', JSON.stringify(auditIssueData));

            // Make AJAX request to create Jira issue
            fetch('<?php echo BASE_URL; ?>/api/perform_audit.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Handle response
                console.log(data);
                // You can add further actions based on the response here
                // For example, show success message or close modal
                var myModal = new bootstrap.Modal(document.getElementById('auditModal'));
                myModal.hide(); // Close modal
            })
            .catch(error => {
                console.error('Error:', error);
                // Handle error
            });
        });
    </script>
<!-- end audit script -->

<!-- maintenance script -->
    <script>
        document.getElementById('maintenanceModalForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            var m_asset_tag = document.getElementById('m_asset_tag').value;
            var m_summary = document.getElementById('m_summary').value;
            var m_notes = document.getElementById('m_notes').value;
            var m_combinedSummary = m_asset_tag + m_summary;
            var maintenanceIssueData = {
                "fields": {
                    "project": {
                        "key": "INFRA"
                    },
                    "summary": m_combinedSummary,
                    "description": {
                        "type": "doc",
                        "version": 1,
                        "content": [
                            {
                                "type": "paragraph",
                                "content": [
                                    {
                                        "type": "text",
                                        "text": m_notes
                                    }
                                ]
                            }
                        ]
                    },
                    "issuetype": {
                        "id": "10030"
                    }
                }
            };

            // Convert issueData to FormData object
            var m_formData = new FormData();
            m_formData.append('maintenanceIssueData', JSON.stringify(maintenanceIssueData));

            // Make AJAX request to create Jira issue
            fetch('<?php echo BASE_URL; ?>/api/create_maintenance.php', {
                method: 'POST',
                body: m_formData
            })
            .then(response => response.text())
            .then(data => {
                // Handle response
                console.log(data);
                // You can add further actions based on the response here
                // For example, show success message or close modal
                var m_myModal = new bootstrap.Modal(document.getElementById('maintenanceModal'));
                m_myModal.hide(); // Close modal
            })
            .catch(error => {
                console.error('Error:', error);
                // Handle error
            });
        });
    </script>
<!-- end maintenance script -->




</body>
</html>
