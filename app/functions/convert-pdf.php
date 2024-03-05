<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


include_once(ROOT_PATH . '/TCPDF/tcpdf.php');

$pdf = new TCPDF('P', 'mm', 'A4');

$pdf->AddPage();

$pdf->Output();



?>