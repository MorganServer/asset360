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
    // $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Create new TCPDF instance
$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<img src="../assets/images/logo-white.png"></img>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // Add a page
    $pdf->AddPage();

    // Add logo and details
    $pdf->Image('../assets/images/logo-white.png', 10, 10, 30, '', 'PNG');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Ln(10); // Add some space after details

    // Create HTML content
    $html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
tr.border_bottom td {
    border-bottom: 1px solid black;
}
</style>
EOF;


    $html .= '<h2>Asset Details</h2>';
    if ($result->num_rows > 0) {
        $html .= '<table cellpadding="5" cellspacing="0">';
        $html .= '<tr class="border_bottom" style="border-bottom: 1px solid black; font-weight:bold;text-align:left;">';
        $html .= '<td>Asset Tag</td>';
        $html .= '<td>Asset Details</td>';
        $html .= '<td>IP Address</td>';
        $html .= '<td>Location</td>';
        $html .= '<td>Status</td>';
        $html .= '</tr>';
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr class="border_bottom" style="border-bottom: 1px solid black;">';
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
