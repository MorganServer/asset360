<?php
// Function to fetch data from the database
function fetchDataFromDatabase($conn) {
    $query = "SELECT * FROM assets";
    $result = mysqli_query($conn, $query);

    $data = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

// Check if the download button is clicked
if (isset($_GET['download'])) {
    // Fetch data from the database
    $data = fetchDataFromDatabase($conn);

    // Generate HTML for the table
    $html = '<table border="1">';
    $html .= '<tr>';
    foreach ($data[0] as $key => $value) {
        $html .= '<th>' . $key . '</th>';
    }
    $html .= '</tr>';
    foreach ($data as $row) {
        $html .= '<tr>';
        foreach ($row as $value) {
            $html .= '<td>' . $value . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';

    // Write HTML content to a temporary file
    $tempHtmlFile = tempnam(sys_get_temp_dir(), 'data_');
    file_put_contents($tempHtmlFile, $html);

    // Execute Python script to convert HTML to PDF using pdfkit
    exec("python convert_to_pdf.py $tempHtmlFile", $output, $returnCode);

    // Delete temporary HTML file
    unlink($tempHtmlFile);

    // Check if PDF conversion was successful
    if ($returnCode === 0) {
        // Set appropriate headers for downloading the PDF file
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="asset_data.pdf"');
        readfile('asset_data.pdf');
        exit;
    } else {
        die("Error converting HTML to PDF.");
    }
}
?>