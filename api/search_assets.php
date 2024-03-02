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
        echo '<ul>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li onclick="selectAsset(\'' . $row['asset_name'] . '\')">' . $row['asset_name'] . '</li>';
        }
        echo '</ul>';
    } else {
        echo 'No assets found';
    }
}
?>
