<?php

require_once 'classes/PDF_Label.php';

$pdf = new PDF_Label('L7163');
$pdf->AddPage();
// Print labels
for($i=1;$i<=20;$i++) {
    $text = sprintf("%s\n%s\n%s\n%s %s, %s", "Laurent $i", 'Immeuble Toto', 'av. Fragonard', '06000', 'NICE', 'FRANCE');
    $pdf->Add_Label($text);
}
$pdf->Output();