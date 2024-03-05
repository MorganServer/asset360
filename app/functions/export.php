<?php
// Function to fetch data from the database
function fetchDataFromDatabase($conn) {
    $query = "SELECT * FROM assets";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    } else {
        return "No data found";
    }
}

// Function to download data as JSON
function downloadData($data) {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="data.json"');
    echo json_encode($data);
    exit;
}

// Check if the download button is clicked
if (isset($_GET['download'])) {
    // Fetch data from the database
    $data = fetchDataFromDatabase($conn);

    // Download data as JSON
    downloadData($data);
}
?>