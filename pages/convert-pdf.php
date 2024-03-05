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
    $pdf->Cell(30, 10, 'Asset Tag No', 0, 0, 'C');
    $pdf->Cell(60, 10, 'Asset Name', 0, 0, 'C'); 
    $pdf->Cell(30, 10, 'IP Address', 0, 0, 'C');
    $pdf->Cell(30, 10, 'Location', 0, 0, 'C');
    $pdf->Cell(20, 10, 'Status', 0, 1, 'C');

    // Set table data
    $pdf->SetFont('helvetica', '', 10);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(30, 10, $row['asset_tag_no'], 0, 0, 'C');
            $html = '<span>' . $row['asset_name'] . '</span><br><span style="color: #999; font-size: 8px;">' . $row['model'] . '</span>';
            $pdf->writeHTMLCell(40, 20, '', '', $html, 0, 0, false, true, 'C');
            $pdf->Cell(40, 10, $row['ip_address'], 0, 0, 'C');
            $pdf->Cell(30, 10, $row['location'], 0, 0, 'C');
            $pdf->Cell(20, 10, $row['status'], 0, 1, 'C');

            // Add a horizontal line
            $pdf->Cell(190, 0, '', 'T');
        }
    } else {
        $pdf->Cell(190, 10, 'No data found', 0, 1, 'C');
    }

    $pdf->Output('data_export.pdf', 'D');

    $conn->close();
    exit(); // Prevent further execution
}
?>
