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
    <link rel="stylesheet" href="../../assets/css/main.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../../assets/css/styles.css?v=<?php echo time(); ?>">

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/7kainuaawjddfzf3pj7t2fm3qdjgq5smjfjtsw3l4kqfd1h4/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

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
        </style>
    <!-- end Styles -->

</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php //include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

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
                        <a class="badge text-bg-primary text-decoration-none me-2" style="font-size: 14px;" data-bs-toggle="modal" data-bs-target="#auditModal"><i class="bi bi-shield-fill-check"></i></a>
                        <a class="badge text-bg-primary text-decoration-none" style="font-size: 14px;" data-bs-toggle="modal" data-bs-target="#maintenanceModal"><i class="bi bi-tools"></i></a>
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
                    <span>
                        <?php if($interview_set == 1 && $watchlist == 0){ ?>
                            <span class="pe-3"></span><i class="bi bi-person-video"></i>
                        <?php } else if($interview_set == 0 && $watchlist == 1) { ?>
                            <span class="pe-3"></span><i class="bi bi-eye-fill"></i>
                        <?php } else if($interview_set == 1 && $watchlist == 1) { ?>
                            <span class="pe-3"></span><i class="bi bi-person-video"></i>&nbsp;&nbsp;<i class="bi bi-eye-fill"></i>
                        <?php } else { }
                        ?>
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
                  <!-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="jira-tab" data-bs-toggle="tab" data-bs-target="#jira-tab-pane" type="button" role="tab" aria-controls="jira-tab-pane" aria-selected="false">Jira Tickets</button>
                  </li> -->
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

                            <div class="con d-flex">
                                <div class=" w-50">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Asset Tag Number</div>
                                            </div>
                                            <span class=""><?php echo $off_asset_tag_no; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Asset Type</div>
                                            </div>
                                            <span class=""><?php echo $off_asset_type; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Serial Number</div>
                                            </div>
                                            <span class=""><?php echo $off_serial_number; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Model</div>
                                            </div>
                                            <span class=""><?php echo $off_model; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Model Number</div>
                                            </div>
                                            <span class=""><?php echo $off_model_no; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Model Number</div>
                                            </div>
                                            <span class=""><?php echo $off_model_no; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Manufacturer Name</div>
                                            </div>
                                            <span class=""><?php echo $off_manufacturer_name; ?></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class=" w-50">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 20%;">
                                                <div class="fw-bold">Asset Tag Number</div>
                                            </div>
                                            <span class=""><?php echo $off_asset_tag_no; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Asset Type</div>
                                            </div>
                                            <span class=""><?php echo $off_asset_type; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Serial Number</div>
                                            </div>
                                            <span class=""><?php echo $off_serial_number; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Model</div>
                                            </div>
                                            <span class=""><?php echo $off_model; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Model Number</div>
                                            </div>
                                            <span class=""><?php echo $off_model_no; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Model Number</div>
                                            </div>
                                            <span class=""><?php echo $off_model_no; ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-start">
                                            <div class="ms-2" style="width: 15%;">
                                                <div class="fw-bold">Manufacturer Name</div>
                                            </div>
                                            <span class=""><?php echo $off_manufacturer_name; ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                    


                        </div>
                    <!-- Notes -->
                        <div class="tab-pane fade" id="notes-tab-pane" role="tabpanel" aria-labelledby="notes-tab" tabindex="0">
                    
                            <div class="mt-4"></div>
                            <h4><i class="bi bi-file-earmark-text-fill"></i> Notes</h4>
                            <div id="accordion">
                                <?php
                                // Check if $notes is not empty
                                if (!empty($notes)) {
                                    // Match all <h5> tags and their content
                                    preg_match_all('/<h5>(.*?)<\/h5>(.*?)(?=<h5>|$)/s', $notes, $matches, PREG_SET_ORDER);
                                
                                    // Loop through each matched note
                                    foreach ($matches as $index => $match) {
                                        // Extract title and content
                                        $title = $match[1];
                                        $content = $match[2];
                                    
                                        // Display accordion item
                                        ?>
                                        <div class="accordion-item">

                                            <h5 class="accordion-header" id="heading<?= $index ?>">
                                                <button class="accordion-button collapsed" id="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="false" aria-controls="collapse<?= $index ?>">
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
                    <!-- Events -->
                        <div class="tab-pane fade" id="events-tab-pane" role="tabpanel" aria-labelledby="events-tab" tabindex="0">
                    


                        </div>
                    <!-- Jira -->
                        <div class="tab-pane fade" id="jira-tab-pane" role="tabpanel" aria-labelledby="jira-tab" tabindex="0">...</div>
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
                                        <input type="hidden" class="form-control" id="completed_by" name="completed_by" value="<?php echo $_SESSION['fname'] . ' ' . $_SESSION['lname']; ?>">
                                        <input type="hidden" class="form-control" id="asset_tag_no" name="asset_tag_no" value="<?php echo $off_asset_tag_no;?>">
                                        <input type="hidden" class="form-control" id="status" name="status" value="Awaiting Approval">
                                        
                                        <div class="row">
                                            <div class="col">
                                                <label for="asset_tag_no" class="form-label fw-bold">Asset Tag Number</label><br>
                                                <?php echo $off_asset_tag_no; ?>
                                            </div>
                                            <div class="col">
                                                <label for="completed_by" class="form-label fw-bold">Completed By</label><br>
                                                <?php echo $_SESSION['fname'] . ' ' . $_SESSION['lname']; ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col">
                                            <?php $cdate = date("Y-m-d"); ?>
                                            <label for="date_completed" class="form-label">Date Completed</label>
                                            <input type="date" class="form-control" id="date_completed" name="date_completed" value="<?php echo $cdate; ?>">
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
                                    <h5 class="modal-title" id="auditModalLabel">Audit Request</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <!-- Modal Body - Your form goes here -->
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" class="form-control" id="event_type" name="event_type" value="Audit">
                                        <input type="hidden" class="form-control" id="completed_by" name="completed_by" value="<?php echo $_SESSION['fname'] . ' ' . $_SESSION['lname']; ?>">
                                        <input type="hidden" class="form-control" id="asset_tag_no" name="asset_tag_no" value="<?php echo $off_asset_tag_no;?>">
                                        <input type="hidden" class="form-control" id="status" name="status" value="Awaiting Approval">

                                        <div class="row">
                                            <div class="col">
                                                <label for="asset_tag_no" class="form-label fw-bold">Asset Tag Number</label><br>
                                                <?php echo $off_asset_tag_no; ?>
                                            </div>
                                            <div class="col">
                                                <label for="completed_by" class="form-label fw-bold">Completed By</label><br>
                                                <?php echo $_SESSION['fname'] . ' ' . $_SESSION['lname']; ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col">
                                            <?php $cdate = date("Y-m-d"); ?>
                                            <label for="date_completed" class="form-label">Date Completed</label>
                                            <input type="date" class="form-control" id="date_completed" name="date_completed" value="<?php echo $cdate; ?>">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
    var accordionButton = document.getElementById('accordion-button');
    if (accordionButton) {
        var chev_i = document.getElementById('chev');
        
        if (chev_i) {
            accordionButton.addEventListener('click', function() {
                
                var isCollapsed = accordionButton.classList.contains('collapsed');
                
                if (isCollapsed) {
                    chev_i.classList.remove('bi-chevron-up');
                    chev_i.classList.add('bi-chevron-down');
                } else {
                    chev_i.classList.remove('bi-chevron-down');
                    chev_i.classList.add('bi-chevron-up');
                }
            });
        } else {
            console.log('Chevron icon not found');
        }
    } else {
        console.log('Accordion button not found');
    }
});

</script>

<script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
