<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Assuming ROOT_PATH is defined elsewhere in your code
include_once(ROOT_PATH . '/TCPDF/tcpdf.php');

// Assuming $conn is your MySQL connection object
$sql = "SELECT * FROM assets";
$result = $conn->query($sql);

$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Data Export');

$pdf->AddPage();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Format the data as needed and add to the PDF
        $pdf->Cell(0, 10, $row['asset_tag_no'] . ' - ' . $row['asset_name'], 0, 1);
    }
} else {
    $pdf->Cell(0, 10, 'No data found', 0, 1);
}

$pdf->Output('data_export.pdf', 'D');

$conn->close();
?>