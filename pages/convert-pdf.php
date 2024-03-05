<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Assuming ROOT_PATH is defined elsewhere in your code
include_once(ROOT_PATH . '/TCPDF/tcpdf.php');

if (isset($_GET['generatePdf'])) {
    // Assuming $conn is your MySQL connection object
    $sql = "SELECT a.asset_tag_no, a.asset_name, a.model, IFNULL(ip.ip_address, '-') AS ip_address, a.location, a.status 
            FROM assets AS a 
            LEFT JOIN ip_address AS ip ON a.asset_tag_no = ip.assigned_asset_tag_no";
    $result = $conn->query($sql);

    $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Data Export');

    $pdf->AddPage();

    // Set table header
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(30, 10, 'Asset Tag No', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Asset Name', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Model', 1, 0, 'C');
    $pdf->Cell(40, 10, 'IP Address', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Location', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Status', 1, 1, 'C');

    // Set table data
    $pdf->SetFont('helvetica', '', 10);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(30, 10, $row['asset_tag_no'], 1, 0, 'C');
            // Use MultiCell for Asset Name and IP Address columns to enable text wrapping
            $pdf->MultiCell(40, 10, $row['asset_name'], 1, 'C');
            $pdf->Cell(30, 10, $row['model'], 1, 0, 'C');
            $pdf->MultiCell(40, 10, $row['ip_address'], 1, 'C');
            $pdf->Cell(30, 10, $row['location'], 1, 0, 'C');
            $pdf->Cell(20, 10, $row['status'], 1, 1, 'C');
        }
    } else {
        $pdf->Cell(190, 10, 'No data found', 1, 1, 'C');
    }

    $pdf->Output('data_export.pdf', 'D');

    $conn->close();
    exit(); // Prevent further execution
}
?>