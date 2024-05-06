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
    <link rel="stylesheet" href="../../assets/css/styles.css?v=<?php echo time(); ?>">

    <title>Notifications | Asset360</title>
</head>
<body>
    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

    <!-- main-container -->
    <div class="container" style="padding: 0 75px 0 75px;">
            <h2 class="mt-4">
                Notifications
            </h2>
            <hr>

            <table class="table">
            <thead>
                <tr>
                <th scope="col">ID #</th>
                <th scope="col">Asset Name</th>
                <th scope="col">Notified Date</th>
                <th scope="col">Acknowledged Date</th>
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
                    
                    $sql = "SELECT * FROM notifications ORDER BY notification_updated DESC LIMIT $limit OFFSET $offset";
                    $result = mysqli_query($conn, $sql);
                    if($result) {
                        $num_rows = mysqli_num_rows($result);
                        if($num_rows > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $id                     = $row['notification_id'];
                                $idno                   = $row['idno'];
                                $notify_date            = $row['notify_date'];
                                $acknowledged           = $row['acknowledged'];
                                $ack_by                 = $row['ack_by'];
                                $ack_date               = $row['ack_date'];
                                $assigned_asset_tag_no  = $row['assigned_asset_tag_no'];
                                $notification_created   = $row['notification_created'];

                                $f_notify_date = !empty($notify_date) ? date_format(date_create($notify_date), 'M d, Y') : '--';
                                $f_ack_date = !empty($ack_date) ? date_format(date_create($ack_date), 'M d, Y') : '--';
                                $f_notification_created = !empty($notification_created) ? date_format(date_create($notification_created), 'M d, Y') : '--';
                ?>
                <tr>
                    <th scope="row"><?php echo $idno; ?></th>
                    <?php
                    // Pagination variables
                    $a_sql = "SELECT * FROM assets WHERE asset_tag_no = '$assigned_asset_tag_no'";
                    $a_result = mysqli_query($conn, $a_sql);
                    if($a_result) {
                        $a_num_rows = mysqli_num_rows($a_result);
                        if($a_num_rows > 0) {
                            while ($a_row = mysqli_fetch_assoc($a_result)) {
                                $asset_name                   = $a_row['asset_name'];
                            }}}
                ?>
                    <td><?php echo $asset_name ? $asset_name : '--'; ?></td>
                    <td><?php echo $f_notify_date ? $f_notify_date : '--'; ?></td>
                    <td><?php echo $f_ack_date ? $f_ack_date : '--'; ?></td>
                    <td>
                        <?php 
                        if ($acknowledged == 0) {
                            echo '<span class="badge text-bg-danger">Open</span>'; // Red dot
                        } elseif ($acknowledged == 1) {
                            echo '<span class="badge text-bg-success">Acknowledged</span>'; // Green dot with "Acknowledged" text
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                    <td style="font-size: 20px;">
                        <!-- <a href="<?php echo BASE_URL; ?>/asset/view/?id=<?php echo $id; ?>" class="view">
                            <i class="bi bi-eye text-success"></i>
                        </a>  -->
                        &nbsp; Add buttons
                        <!-- <a href="<?php echo BASE_URL; ?>/asset/update/?id=<?php echo $id; ?>">
                            <i class="bi bi-pencil-square" style="color:#005382;"></i>
                        </a>  -->
                        &nbsp; 
                        <!-- <a href="<?php echo BASE_URL; ?>/asset/delete/?id=<?php echo $id; ?>" class="delete">
                            <i class="bi bi-trash" style="color:#941515;"></i>
                        </a> -->
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
            $sql = "SELECT COUNT(*) as total FROM notifications";
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