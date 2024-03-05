<?php
// Turn on error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include TCPDF library
include_once(ROOT_PATH . '/TCPDF/tcpdf.php');

// Check if PDF generation is requested
if (isset($_GET['generatePdf'])) {
    // Your MySQL query
    $sql = "SELECT a.asset_tag_no, a.asset_name, a.model, IFNULL(ip.ip_address, '-') AS ip_address, a.location, a.status 
            FROM assets AS a 
            LEFT JOIN ip_address AS ip ON a.asset_tag_no = ip.assigned_asset_tag_no";

    // Execute the query
    $result = $conn->query($sql);

    // Create new TCPDF instance
    $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set creator and title
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Data Export');

    // Add a page
    $pdf->AddPage();

    // Add logo and details
    $pdf->Image('path/to/your/logo.png', 10, 10, 30, '', 'PNG');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Your Company Name', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Address: Your Company Address', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Phone: Your Company Phone Number', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Email: Your Company Email Address', 0, 1, 'C');
    $pdf->Ln(10); // Add some space after details

    // Set table header
    $pdf->Cell(30, 10, 'Asset Tag No', 1, 0, 'C');
    $pdf->Cell(60, 10, 'Asset Name', 1, 0, 'C');
    $pdf->Cell(30, 10, 'IP Address', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Location', 1, 0, 'C');
    $pdf->Cell(20, 10, 'Status', 1, 1, 'C');

    // Set table data
    $pdf->SetFont('helvetica', '', 10);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(30, 10, $row['asset_tag_no'], 1, 0, 'C');
            $html = '<span>' . $row['asset_name'] . '</span><br><span style="color: #999; font-size: 8px;">' . $row['model'] . '</span>';
            $pdf->writeHTMLCell(40, 20, '', '', $html, 1, 0, false, true, 'C');
            $pdf->Cell(40, 10, $row['ip_address'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['location'], 1, 0, 'C');
            $pdf->Cell(20, 10, $row['status'], 1, 1, 'C');
        }
    } else {
        $pdf->Cell(190, 10, 'No data found', 1, 1, 'C');
    }

    // Output the PDF as a file to download
    $pdf->Output('data_export.pdf', 'D');

    // Close MySQL connection
    $conn->close();
    exit(); // Prevent further execution
}
?>
