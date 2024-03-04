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
    
    $sql = "SELECT assets.*, ip_address.*
            FROM assets
            LEFT JOIN ip_address ON assets.asset_tag_no = ip_address.assigned_asset_tag_no
            ORDER BY assets.created_at DESC
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

                $ms_date = date_create($maintenance_schedule);
                $f_maintenance_schedule = date_format($ms_date, 'M d, Y');
                $as_date = date_create($audit_schedule);
                $f_audit_schedule = date_format($as_date, 'M d, Y');
?>
<tr>
    <th scope="row"><?php echo $asset_tag_no; ?></th>
    <td><?php echo $asset_name ? $asset_name : '-'; ?></td>
    <td><?php echo $ip_address ? $ip_address : '-'; ?></td>
    <td><?php echo $asset_type ? $asset_type : '-'; ?></td>
    <td><?php echo $status ? $status : '-'; ?></td>
    <td style="font-size: 20px;"><a href="view-app.php?viewid=<?php echo $id; ?>" class="view"><i class="bi bi-eye text-success"></i></a> &nbsp; <a href="update-app.php?updateid=<?php echo $id; ?>"><i class="bi bi-pencil-square" style="color:#005382;"></a></i> &nbsp; <a href="open-app.php?deleteid=<?php echo $id; ?>" class="delete"><i class="bi bi-trash" style="color:#941515;"></i></a></td>
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
            $sql = "SELECT COUNT(*) as total FROM assets WHERE asset_type = 'Computer'";
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