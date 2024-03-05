<?php
// Include the TCPDF library


// // Connect to MySQL database
// $servername = "localhost";
// $username = "username";
// $password = "password";
// $dbname = "database_name";

// $conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the MySQL database
$sql = "SELECT * FROM assets";
$result = $conn->query($sql);

// Initialize PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Data Export');

// Add a page
$pdf->AddPage();

// Loop through the data and output to PDF
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Format the data as needed and add to the PDF
        $pdf->Cell(0, 10, $row['asset_name'] . ' - ' . $row['column2'], 0, 1);
    }
} else {
    $pdf->Cell(0, 10, 'No data found', 0, 1);
}

// Close and output PDF
$pdf->Output('data_export.pdf', 'D');

// Close MySQL connection
$conn->close();
?>