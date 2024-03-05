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
    $pdf->Ln(25); // Add some space after details

    // Create HTML content
    $html = '<h2>Asset Details</h2>';
    if ($result->num_rows > 0) {
        $html .= '<table cellpadding="5" cellspacing="0">';
        $html .= '<tr style="font-weight:bold;text-align:left;">';
        $html .= '<td>Asset Tag</td>';
        $html .= '<td>Asset Details</td>';
        $html .= '<td>IP Address</td>';
        $html .= '<td>Location</td>';
        $html .= '<td>Status</td>';
        $html .= '</tr>';
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr style="border-bottom: 1px solid black;">';
            $html .= '<td>' . $row['asset_tag_no'] . '</td>';
            $html .= '<td>' . $row['asset_name'] . '<br><span style="font-size: 6px; color: #999;">' . $row['model'] . '</span></td>';
            $html .= '<td>' . $row['ip_address'] . '</td>';
            $html .= '<td>' . $row['location'] . '</td>';
            $html .= '<td>' . $row['status'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
    } else {
        $html .= '<p>No assets found.</p>';
    }

    // Output the HTML content to the PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Output the PDF as a file to download
    $pdf->Output('data_export.pdf', 'D');

    // Close MySQL connection
    $conn->close();
    exit(); // Prevent further execution
}
?>
