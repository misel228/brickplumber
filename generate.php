<?php
namespace misel228\brickplumber;


require('vendor/autoload.php');
require('my_pdf.php');
require('scan_codes.php');

$pdf = new MyPdf($CONFIG['colors']);

$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Lego Super Mario Codes! '.date('Y-m-d H:i:s'));

$codes = array_merge($CONFIG['codes'], $CONFIG['codes'], $CONFIG['codes']);
$pdf->draw_codes($CONFIG['codes']);
$pdf->Output('F', 'test.pdf');
//*/

$pdf = new MyPdf($CONFIG['colors'], false);

$pdf->AddPage();

$codes = array_merge($CONFIG['codes'], $CONFIG['codes'], $CONFIG['codes']);
$pdf->draw_codes($CONFIG['codes']);
$pdf->Output('F', 'codes.pdf');
