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
    $pdf->Image('../assets/images/logo-white.png', 10, 10, 30, '', 'PNG');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Ln(10); // Add some space after details

    // Create HTML content
    $html = '<h2>Asset Details</h2><ol>';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $html .= '<li>';
            $html .= '<strong>Asset Tag No:</strong> ' . $row['asset_tag_no'] . '<br>';
            $html .= '<strong>Asset Name:</strong> ' . $row['asset_name'] . '<br>';
            $html .= '<strong>Model:</strong> ' . $row['model'] . '<br>';
            $html .= '<strong>IP Address:</strong> ' . $row['ip_address'] . '<br>';
            $html .= '<strong>Location:</strong> ' . $row['location'] . '<br>';
            $html .= '<strong>Status:</strong> ' . $row['status'] . '<br>';
            $html .= '</li>';
        }
    } else {
        $html .= '<li>No assets found.</li>';
    }
    $html .= '</ol>';

    // Output the HTML content to the PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Output the PDF as a file to download
    $pdf->Output('data_export.pdf', 'D');

    // Close MySQL connection
    $conn->close();
    exit(); // Prevent further execution
}
?>
