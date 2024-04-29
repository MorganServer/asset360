<?php
date_default_timezone_set('America/Denver');
require_once "../app/database/connection.php";
require_once "../path.php";
session_start();

$files = glob("../app/functions/*.php");
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
    <!-- <link rel="stylesheet" href="../assets/css/main.css?v=<?php //echo time(); ?>"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo time(); ?>">

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/7kainuaawjddfzf3pj7t2fm3qdjgq5smjfjtsw3l4kqfd1h4/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

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
            .btn-group.dropend .dropdown-toggle::after {
                display: none;
            }
            .btn-group.dropend .dropdown-toggle:focus {
                outline: none;
            }
        </style>
    <!-- end Styles -->

</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

<div class="container-fluid" style="margin-top: 60px;">
    <div class="application-details">

        <h2>
            Event Log
        </h2>



                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="maintenance-tab" data-bs-toggle="tab" data-bs-target="#maintenance-tab-pane" type="button" role="tab" aria-controls="maintenance-tab-pane" aria-selected="true">Maintenance</button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="audit-tab" data-bs-toggle="tab" data-bs-target="#Audit-tab-pane" type="button" role="tab" aria-controls="Audit-tab-pane" aria-selected="false">Audit</button>
                  </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Maintenance -->
                        <div class="tab-pane fade show active" id="maintenance-tab-pane" role="tabpanel" aria-labelledby="maintenance-tab" tabindex="0">
                            <div class="mt-4"></div>
                            <h4><i class="bi bi-tools"></i> Maintenance Events</h4>
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
                                    <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // Pagination variables
                                        $limit = 10; // Number of entries per page
                                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                        $offset = ($page - 1) * $limit;

                                        $msql = "SELECT * FROM event_log WHERE event_type = 'Maintenance' ORDER BY event_created DESC LIMIT $limit OFFSET $offset";
                                        $mresult = mysqli_query($conn, $msql);
                                        if($mresult) {
                                            $mnum_rows = mysqli_num_rows($mresult);
                                            if($mnum_rows > 0) {
                                                while ($mrow = mysqli_fetch_assoc($mresult)) {
                                                    $id                     = $mrow['event_id'];
                                                    $idno                   = $mrow['idno'];
                                                    $status                 = $mrow['status'];
                                                    $date_performed         = $mrow['date_performed'];
                                                    $date_reviewed          = $mrow['date_reviewed'];
                                                    $asset_tag_no           = $mrow['asset_tag_no'];
                                                    $performed_by           = $mrow['performed_by'];
                                                    $reviewed_by            = $mrow['reviewed_by'];
                                                    $event_type             = $mrow['event_type'];
                                                    $notes                  = $mrow['notes'];
                                                    $event_created          = $mrow['event_created'];    
                                                    $event_updated          = $mrow['event_updated'];                 

                                                    // Format maintenance schedule if not null
                                                    $f_date_reviewed = !empty($date_reviewed) ? date_format(date_create($date_reviewed), 'M d, Y') : '-';                  

                                                    // Format audit schedule if not null
                                                    $f_date_performed = !empty($date_performed) ? date_format(date_create($date_performed), 'M d, Y') : '-';
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
                                        <td style="font-size: 20px;"><a href="<?php echo BASE_URL; ?>/asset/view/?id=<?php echo $id; ?>" class="view"><i class="bi bi-eye text-success"></i></a> &nbsp; <a href="update-app.php?updateid=<?php echo $id; ?>"><i class="bi bi-pencil-square" style="color:#005382;"></a></i> &nbsp; <a href="open-app.php?deleteid=<?php echo $id; ?>" class="delete"><i class="bi bi-trash" style="color:#941515;"></i></a></td>
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
                                // Pagination links
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


                        </div>
                    <!-- end Maintenance -->

                    <!-- Audit -->
                        <div class="tab-pane fade" id="Audit-tab-pane" role="tabpanel" aria-labelledby="Audit-tab" tabindex="0">
                            <div class="mt-4"></div>
                            <h4><i class="bi bi-tools"></i> Audit Events</h4>
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
                                    <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // Pagination variables
                                        $limit = 10; // Number of entries per page
                                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                        $offset = ($page - 1) * $limit;

                                        $msql = "SELECT * FROM event_log WHERE event_type = 'Audit' ORDER BY event_created DESC LIMIT $limit OFFSET $offset";
                                        $mresult = mysqli_query($conn, $msql);
                                        if($mresult) {
                                            $mnum_rows = mysqli_num_rows($mresult);
                                            if($mnum_rows > 0) {
                                                while ($mrow = mysqli_fetch_assoc($mresult)) {
                                                    $id                     = $mrow['event_id'];
                                                    $idno                   = $mrow['idno'];
                                                    $status                 = $mrow['status'];
                                                    $date_performed         = $mrow['date_performed'];
                                                    $date_reviewed          = $mrow['date_reviewed'];
                                                    $asset_tag_no           = $mrow['asset_tag_no'];
                                                    $performed_by           = $mrow['performed_by'];
                                                    $reviewed_by            = $mrow['reviewed_by'];
                                                    $event_type             = $mrow['event_type'];
                                                    $notes                  = $mrow['notes'];
                                                    $event_created          = $mrow['event_created'];    
                                                    $event_updated          = $mrow['event_updated'];                 

                                                    // Format maintenance schedule if not null
                                                    $f_date_reviewed = !empty($date_reviewed) ? date_format(date_create($date_reviewed), 'M d, Y') : '-';                  

                                                    // Format audit schedule if not null
                                                    $f_date_performed = !empty($date_performed) ? date_format(date_create($date_performed), 'M d, Y') : '-';
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
                                        <!-- <td style="font-size: 20px;"></i><a href="<?php //echo BASE_URL; ?>/asset/view/?id=<?php //echo $id; ?>" class="view"><i class="bi bi-eye text-success"></i></a> &nbsp; <a href="open-app.php?deleteid=<?php //echo $id; ?>" class="delete"><i class="bi bi-trash" style="color:#941515;"></i></a></td> -->

                                        <td style="font-size: 20px;">
                                            <div class="btn-group dropend">
                                                <button class="btn btn-link text-decoration-none dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots text-secondary"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/asset/view/?id=<?php echo $id; ?>"><i class="bi bi-eye text-success"></i> View</a></li>
                                                    <li><a class="dropdown-item" href="open-app.php?deleteid=<?php echo $id; ?>"><i class="bi bi-trash" style="color:#941515;"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>

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
                                // Pagination links
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


                        </div>
                    <!-- end Audit -->


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
            
    </div>
</div>



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
