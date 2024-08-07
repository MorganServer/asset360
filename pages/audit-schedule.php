<?php
date_default_timezone_set('America/Denver');
require_once "../app/database/connection.php";
// require_once "app/functions/add_app.php";
require_once "../path.php";
session_start();

$files = glob("../app/functions/*.php");
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

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo time(); ?>">

    <title>Audit Schedule | Asset360</title>
</head>
<body>
    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

    <!-- main-container -->
        <div class="container" style="padding: 0 75px 0 75px;">
            <h2 class="mt-4">
                Audit Schedule
            </h2>
            <hr>

            <table class="table">
            <thead>
                <tr>
                <th scope="col">Tag No</th>
                <th scope="col">Asset Name</th>
                <th scope="col">Next Audit</th>
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

                    $today = date('Y-m-d');
                    
                    $audit_sql = "SELECT * FROM assets ORDER BY audit_schedule ASC LIMIT $limit OFFSET $offset";
                    $audit_result = mysqli_query($conn, $audit_sql);
                    if($audit_result) {
                        $audit_num_rows = mysqli_num_rows($audit_result);
                        if($audit_num_rows > 0) {
                            while ($audit_row = mysqli_fetch_assoc($audit_result)) {
                                $id                     = $audit_row['asset_id'];
                                $idno                   = $audit_row['idno'];
                                $status                 = $audit_row['status'];
                                $asset_name             = $audit_row['asset_name'];
                                $asset_tag_no           = $audit_row['asset_tag_no'];
                                $maintenance_schedule   = $audit_row['maintenance_schedule'];
                                $audit_schedule         = $audit_row['audit_schedule'];
                                $location               = $audit_row['location'];
                                $created_at             = $audit_row['created_at'];

                                // Format audit schedule if not null
                                $f_audit_schedule = !empty($audit_schedule) ? date_format(date_create($audit_schedule), 'M d, Y') : '-';
                                $is_today = ($audit_schedule == $today) ? true : false;
                ?>
                <tr <?php if (strtotime($audit_schedule) <= strtotime($today)) echo 'class="table-info"'; ?>>
                    <th scope="row"><?php echo $asset_tag_no; ?></th>
                    <td><?php echo $asset_name ? $asset_name : '-'; ?></td>
                    <td><?php echo $f_audit_schedule ? $f_audit_schedule : '-'; ?></td>
                    <td><?php echo $status ? $status : '-'; ?></td>
                    <td>
                        <?php if (strtotime($audit_schedule) <= strtotime($today)) { ?>
                            <a class="badge text-bg-primary text-decoration-none me-2" style="font-size: 14px; cursor: pointer;" id="createTicketButton" data-bs-toggle="modal" data-bs-target="#auditModal<?php echo $id; ?>">
                                Perform
                            </a>
                        <?php } else { ?>
                        <a class="badge text-bg-secondary text-decoration-none me-2" style="font-size: 14px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#rescheduleModal<?php echo $id; ?>">
                            Reschedule
                        </a>
                        <?php } ?>
                    </td>                   
                </tr>

                <!-- AUDIT modal -->
                    <div class="modal fade" id="auditModal<?php echo $id; ?>" tabindex="-1" aria-labelledby="auditModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="auditModalLabel">Perform Audit</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="auditModalForm">
                                    <?php
                                        $as_sql = "SELECT * FROM assets WHERE asset_id = $id";
                                        $as_result = mysqli_query($conn, $as_sql);
                                        if($as_result) {
                                            $as_num_rows = mysqli_num_rows($as_result);
                                            if($as_num_rows > 0) {
                                                while ($as_row = mysqli_fetch_assoc($as_result)) {
                                                    $as_id                  = $as_row['asset_id'];
                                                    $as_asset_tag_no           = $as_row['asset_tag_no'];
                                                    $as_maintenance_schedule   = $as_row['maintenance_schedule'];
                                                    $as_audit_schedule         = $as_row['audit_schedule'];
                                                    $as_location               = $as_row['location'];
                                                    $as_created_at             = $as_row['created_at'];
                                    ?>

                                        <input type="hidden" class="form-control" id="asset_tag" name="asset_tag" value="<?php echo '[' . $as_asset_tag_no. '] '; ?>">
                                        <input type="hidden" class="form-control" id="actual_asset_tag" name="actual_asset_tag" value="<?php echo $as_asset_tag_no; ?>">
                                        <input type="hidden" class="form-control" id="asset_id" name="asset_id" value="<?php echo $as_id; ?>">
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
                                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Perform</button>
                                        <?php }}} ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- End Modal for AUDIT -->

                <!-- RESCHEDULE modal -->
                    <div class="modal fade" id="rescheduleModal<?php echo $id; ?>" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rescheduleModalLabel">Reschedule Audit</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                    <?php
                                        $r_sql = "SELECT * FROM assets WHERE asset_id = $id";
                                        $r_result = mysqli_query($conn, $r_sql);
                                        if($r_result) {
                                            $r_num_rows = mysqli_num_rows($r_result);
                                            if($r_num_rows > 0) {
                                                while ($r_row = mysqli_fetch_assoc($r_result)) {
                                                    $r_id                       = $r_row['asset_id'];
                                                    $r_asset_tag_no             = $r_row['asset_tag_no'];
                                                    $r_maintenance_schedule     = $r_row['maintenance_schedule'];
                                                    $r_audit_schedule           = $r_row['audit_schedule'];
                                                    $r_location                 = $r_row['location'];
                                                    $r_created_at               = $r_row['created_at'];
                                    ?>
                                        <input type="hidden" class="form-control" id="asset_id" name="asset_id" value="<?php echo $r_id; ?>">
                                        <div class="mb-3">
                                            <label for="audit_schedule" class="form-label" style="font-size: 14px;">Audit Schedule Date</label>
                                            <input type="date" class="form-control" id="audit_schedule" name="audit_schedule" required value="<?php echo $r_audit_schedule; ?>">
                                        </div>
                                        <!-- Add more fields as needed -->
                                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" name="reschedule">Reschedule</button>
                                        <?php }}} ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- End Modal for RESCHEDULE -->

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
            $sql = "SELECT COUNT(*) as total FROM assets WHERE asset_type = 'Server'";
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


<!-- audit script -->
    <script>
        document.getElementById('auditModalForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form submission
        var actual_asset_tag = document.getElementById('actual_asset_tag').value;
        var asset_id = document.getElementById('asset_id').value;
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
                },
                "labels": [
                    actual_asset_tag,
                    asset_id
                ]
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
            // Close modal
            var myModal = new bootstrap.Modal(document.getElementById('auditModal'));
            myModal.hide();
        })
        .catch(error => {
            console.error('Error:', error);
            // Handle error
        });
    });
    </script>
<!-- end audit script -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>