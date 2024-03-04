// Function to output CSV data
function outputCSV($data) {
    $output = fopen("php://output", "w");
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
}

// Fetch data from the database
$sql = "SELECT assets.asset_tag_no, assets.asset_name, ip_address.ip_address, assets.asset_type, assets.status
        FROM assets
        LEFT JOIN ip_address ON assets.asset_tag_no = ip_address.assigned_asset_tag_no
        ORDER BY assets.created_at ASC";
$result = mysqli_query($conn, $sql);

// Check if there are rows in the result
if (mysqli_num_rows($result) > 0) {
    // Create an array to hold the data
    $data = array();

    // Add headers to the data array
    $data[] = array('Tag No', 'Asset Name', 'IP Address', 'Type', 'Status');

    // Add rows from the result to the data array
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            $row['asset_tag_no'],
            $row['asset_name'],
            $row['ip_address'] ? $row['ip_address'] : '-',
            $row['asset_type'] ? $row['asset_type'] : '-',
            $row['status'] ? $row['status'] : '-'
        );
    }

    // Export data to CSV
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=asset_inventory.csv");
    outputCSV($data);
    exit();
} else {
    echo "No data found to export.";
}