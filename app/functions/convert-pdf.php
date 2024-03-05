<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


include_once(ROOT_PATH . '/TCPDF/tcpdf.php');

// Retrieve data from the MySQL database
// Assuming $conn is your MySQL connection object
$sql = "SELECT * FROM assets";
$result = $conn->query($sql);

// Initialize PDF
$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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



?>