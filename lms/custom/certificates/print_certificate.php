<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/webprint/WebClientPrint.php';

use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\PrintFile;
use Neodynamic\SDK\Web\ClientPrintJob;

$certs = $_POST['certs']; // string
$certs_arr = explode(',', $certs);
$cpj = new ClientPrintJob();
$cpj->clientPrinter = new DefaultPrinter();

foreach ($certs_arr as $cert) {
    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates/' . $cert . '/certificate.pdf';
    $fileName = uniqid() . '.pdf';
    echo "Certificate file path: " . $filePath . "<br>";
    //echo "Temp file name: " . $fileName . "<br>";
    
    /*
     * 
    $cpj->printFile = new PrintFile($filePath, $fileName, null);
    ob_start();
    ob_clean();
    echo $cpj->sendToClient();
    ob_end_flush();
    exit();
     * 
     */
}

?>

