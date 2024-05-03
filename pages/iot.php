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

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo time(); ?>">

    <title>IOT Devices | Asset360</title>
</head>
<body>
    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

    <!-- main-container -->
    <div class="container" style="padding: 0 75px 0 75px;">
            <h2 class="mt-4">
                IOT Devices
            </h2>
            <hr>

            <table class="table">
            <thead>
                <tr>
                <th scope="col">Tag No</th>
                <th scope="col">Asset Name</th>
                <th scope="col">Location</th>
                <th scope="col">Last Maintenance</th>
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
                    
                    $sql = "SELECT * FROM assets WHERE asset_type = 'IOT Device' ORDER BY asset_created DESC LIMIT $limit OFFSET $offset";
                    $result = mysqli_query($conn, $sql);
                    if($result) {
                        $num_rows = mysqli_num_rows($result);
                        if($num_rows > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $id                     = $row['asset_id'];
                                $idno                   = $row['idno'];
                                $status                 = $row['status'];
                                $asset_name             = $row['asset_name'];
                                $asset_tag_no           = $row['asset_tag_no'];
                                $maintenance_schedule   = $row['maintenance_schedule'];
                                $audit_schedule         = $row['audit_schedule'];
                                $location               = $row['location'];
                                $created_at             = $row['created_at'];

                                // Format maintenance schedule if not null
                                $f_maintenance_schedule = !empty($maintenance_schedule) ? date_format(date_create($maintenance_schedule), 'M d, Y') : '-';

                                // Format audit schedule if not null
                                $f_audit_schedule = !empty($audit_schedule) ? date_format(date_create($audit_schedule), 'M d, Y') : '-';
                ?>
                <tr>
                    <th scope="row"><?php echo $asset_tag_no; ?></th>
                    <td><?php echo $asset_name ? $asset_name : '-'; ?></td>
                    <td><?php echo $location ? $location : '-'; ?></td>
                    <?php
                    // Function to get Jira issues
                    function getAllJiraIssues($url) {
                        $jiraUsername = "garrett.morgan.pro@gmail.com";
                        $one = "ATATT3xFfGF0rALQ3ASzKULCbilrrrykWqEfW8yJlCjhGCHW0mBSQcSaGP";
                        $two = "Ewxq8DC39D1ElsXBo7Wp3tHueO26Jp3AZ2IQNmfrq5urdZ91wfhGWB5xWd";
                        $three = "gTqWD8qGQ7qbt-CcgAp4WWeSlO0WrN30VB238osKbafBEBHz1WPbUSWeyh";
                        $four = "dXhAHA2kA=46A5779A";
                        $jiraApiToken = $one . $two . $three . $four;
                    
                        $contextOptions = array(
                            'http' => array(
                                'method' => 'GET',
                                'header' => "Content-Type: application/json\r\n" .
                                            "Authorization: Basic " . base64_encode($jiraUsername . ':' . $jiraApiToken) . "\r\n",
                                'ignore_errors' => true 
                            )
                        );
                    
                        $context = stream_context_create($contextOptions);
                    
                        $response = file_get_contents($url, false, $context);
                    
                        return $response;
                    }
                    // Construct the JQL query string dynamically
                    $assetTag = $off_asset_tag_no; // Assuming $off_asset_tag_no contains the current asset tag
                    $jqlQuery = "project in (SG, INFRA) AND summary~'$assetTag' AND issueType = 10030 ORDER BY created DESC";
                    $fields = "summary, issuetype, created, updated, labels, status, duedate"; // Define the fields you want to retrieve
                    // Construct the URL for the Jira API endpoint
                    $url = "https://garrett-morgan.atlassian.net/rest/api/3/search?jql=" . urlencode($jqlQuery) . "&fields=" . urlencode($fields) . "&maxResults=1";
                    // Get Jira issues
                    $response = getAllJiraIssues($url);
                    // Check if the request was successful
                    if ($response !== false) {
                        // Decode the JSON response
                        $data = json_decode($response, true);
                        // Check if issues are found
                        if (isset($data['issues']) && !empty($data['issues'])) {
                            // Get the latest issue
                            $latestIssue = $data['issues'][0];
                            // Display the last maintenance date from Jira (assuming 'updated' field is the maintenance date)
                            $lastMaintenanceDate = isset($latestIssue['fields']['updated']) ? date('M d, Y', strtotime($latestIssue['fields']['updated'])) : '--';
                            $key = $latestIssue['key'];
                        } else {
                            // If no issues are found
                            $lastMaintenanceDate = '--';
                        }
                    } else {
                        // Handle the error
                        $lastMaintenanceDate = 'Failed to fetch data from Jira API';
                    }
                    ?>
                    <td><?php echo $lastMaintenanceDate; ?></td>
                    <td><?php echo $f_audit_schedule ? $f_audit_schedule : '-'; ?></td>
                    <td><?php echo $status ? $status : '-'; ?></td>
                    <td style="font-size: 20px;">
                        <a href="<?php echo BASE_URL; ?>/asset/view/?id=<?php echo $id; ?>" class="view">
                            <i class="bi bi-eye text-success"></i>
                        </a> 
                        &nbsp; 
                        <a href="<?php echo BASE_URL; ?>/asset/update/?id=<?php echo $id; ?>">
                            <i class="bi bi-pencil-square" style="color:#005382;"></i>
                        </a> 
                        &nbsp; 
                        <a href="<?php echo BASE_URL; ?>/asset/delete/?id=<?php echo $id; ?>" class="delete">
                            <i class="bi bi-trash" style="color:#941515;"></i>
                        </a>
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
            $sql = "SELECT COUNT(*) as total FROM assets WHERE asset_type = 'IOT Device'";
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

</body>
</html>