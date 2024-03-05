<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);


// include_once(ROOT_PATH . '/TCPDF/tcpdf.php');


// $sql = "SELECT * FROM assets";
// $result = $conn->query($sql);

// $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// $pdf->SetCreator(PDF_CREATOR);
// $pdf->SetTitle('Data Export');

// $pdf->AddPage();


// if ($result->num_rows > 0) {
//     while($row = $result->fetch_assoc()) {
//         // Format the data as needed and add to the PDF
//         $pdf->Cell(0, 10, $row['asset_tag_no'] . ' - ' . $row['asset_name'], 0, 1);
//     }
// } else {
//     $pdf->Cell(0, 10, 'No data found', 0, 1);
// }

// $pdf->Output('data_export.pdf', 'D');

// $conn->close();


// Check if the request was made via AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    // Proceed with generating the PDF

    // Include necessary files and establish database connection
    include_once(ROOT_PATH . '/TCPDF/tcpdf.php');
    // Assuming $conn is your database connection object

    // Retrieve data from the MySQL database
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

    // Output the PDF as a file
    $pdf->Output('data_export.pdf', 'F');

    // Close MySQL connection
    $conn->close();

    // Send a success response
    http_response_code(200);
    exit;
} else {
    // If the request was not made via AJAX, redirect or handle it accordingly
    header('Location:' . BASE_URL . '/');
    exit;
}



?>
