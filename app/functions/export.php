<?php
// Include the necessary files to connect to the database
require_once '../database/connection.php'; // File containing database connection details

// Function to fetch data from the database
function fetchDataFromDatabase() {
    
    // Query to fetch data from your desired table
    $sql = "SELECT * FROM your_table";
    $result = mysqli_query($conn, $sql);

    // Check if there are rows returned
    if (mysqli_num_rows($result) > 0) {
        // Fetch data and store in an array
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        // Close connection
        mysqli_close($conn);
        
        return $data;
    } else {
        // No data found
        return false;
    }
}

// Function to download data as CSV
function downloadDataAsCSV($data) {
    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="data.csv"');
    
    // Open output stream
    $output = fopen('php://output', 'w');
    
    // Write CSV headers
    fputcsv($output, array_keys($data[0]));
    
    // Write CSV data
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    // Close output stream
    fclose($output);
}

// Trigger download when the button is clicked
if (isset($_POST['download'])) {
    // Fetch data from database
    $data = fetchDataFromDatabase();
    
    // Check if data is available
    if ($data) {
        // Download data as CSV
        downloadDataAsCSV($data);
        exit(); // Stop script execution after download
    } else {
        echo "No data found to download.";
    }
}
?>