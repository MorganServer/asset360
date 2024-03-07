<?php
date_default_timezone_set('America/Denver');
require_once "app/database/connection.php";
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
    <link rel="stylesheet" href="assets/css/main.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

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

<?php include(ROOT_PATH . "/app/database/includes/header.php"); ?>

<div class="container-fluid main">
    <div class="application-details">
        <!-- php code for getting asset details -->
            <?php
            $id = $_GET['viewid'];
            $off_sql = "SELECT assets.*, ip_address.ip_address AS ip_address
            FROM assets
            LEFT JOIN ip_address ON assets.asset_tag_no = ip_address.assigned_asset_tag_no
            WHERE asset_id = $id
            ORDER BY assets.created_at ASC";
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
                }
            // }}
            ?>
        <!-- end php code for getting asset details -->

                <h2>
                    <?php echo $off_asset_name; ?>
                    <span class="ps-3" style="font-size: 14px;">
                        <?php if($status == 'Applied'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-primary"></i> &nbsp; <?php echo $status; ?></span>
                        <?php } else if($status == 'Interviewed'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-info"></i> &nbsp; <?php echo $status; ?></span>
                        <?php } else if($status == 'Offered'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-success"></i> &nbsp; <?php echo $status; ?></span>
                        <?php } else if($status == 'Rejected'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-danger"></i> &nbsp; <?php echo $status; ?></span>
                        <?php } else if($status == 'Interested'){ ?>
                            <span><i style="font-size: 12px;" class="bi bi-circle-fill text-secondary"></i> &nbsp; <?php echo $status; ?></span>
                        <?php } ?>
                    </span>
                    <span class="float-end d-flex">
                        <a class="badge text-bg-primary text-decoration-none me-1" style="font-size: 14px;" href="update-app.php?updateid=<?php echo $id; ?>"><i class="bi bi-shield-fill-check"></i></a>
                        <a class="badge text-bg-primary text-decoration-none" style="font-size: 14px;" href="update-app.php?updateid=<?php echo $id; ?>"><i class="bi bi-tools"></i></a>
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
                        Applied: <?php echo $created_at_formatted; ?>
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
                
                <h4><i class="bi bi-briefcase-fill"></i> Job details</h4>


                <div class="card-container">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Company</h5>
                            <p class="card-text"><?php echo $company; ?></p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Location</h5>
                            <p class="card-text"><?php echo $location; ?></p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Job Type</h5>
                            <p class="card-text"><?php echo $job_type; ?></p>
                        </div>
                    </div>
                </div>

                <div class="card-container">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Base Pay</h5>
                            <p class="card-text">
                                <?php if(!empty($pay)) { ?>
                                    $<?php echo $pay; ?>
                                <?php } else { ?>
                                <span class="text-warning">No base pay found.</span>
                                <?php } ?>
                            </p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Bonus Pay</h5>
                            <p class="card-text">
                                <?php if(!empty($bonus_pay)) { ?>
                                    $<?php echo $bonus_pay; ?>
                                <?php } else { ?>
                                <span class="text-warning">No bonus pay found.</span>
                                <?php } ?>
                            </p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Job Listing</h5>
                            <p class="card-text"><a href="<?php echo $app_link; ?>" target="_blank" class="badge text-bg-secondary text-decoration-none" style="margin-top: -10px !important; padding: none !important;">Visit</a></p>
                        </div>
                    </div>
                </div>

                <h4><i class="bi bi-file-earmark-text-fill"></i> Notes</h4>
                
                <!-- __________ -->
                
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
