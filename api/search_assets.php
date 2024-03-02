<?php
// Include your database connection file
require_once "../app/database/connection.php";

if (isset($_POST['keyword'])) {
    $keyword = $_POST['keyword'];

    // Perform a database query to fetch assets matching the keyword
    $query = "SELECT * FROM assets WHERE asset_name LIKE '%$keyword%'";
    $result = mysqli_query($conn, $query);

    // Display dropdown menu with matching assets
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Output each asset as a link with onclick event to selectAsset function
            echo '<a href="#" class="list-group-item list-group-item-action asset-link" onclick="selectAsset(' . $row['asset_id'] . ', \'' . $row['asset_name'] . '\', \'' . $row['asset_tag_no'] . '\')">' . $row['asset_name'] . '</a>';

        }
    } else {
        echo 'No assets found';
    }
}
?>
