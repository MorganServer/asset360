<?php
// Include your database connection file
require_once "../app/database/connection.php";

// Fetch assets based on the query
if(isset($_POST['query'])){
    $query = $_POST['query'];
    
    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT * FROM assets WHERE asset_tag_no LIKE ?");
    $stmt->execute(["%$query%"]);
    $assets = $stmt->fetchAll();

    // Display search results
    if($stmt->rowCount() > 0){
        foreach($assets as $asset){
            echo '<p>' . $asset['asset_tag_no'] . '</p>'; // Modify this to display relevant asset information
        }
    } else {
        echo '<p>No matching assets found</p>';
    }
}
?>