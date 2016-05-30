<?php

error_reporting(0);
include 'WebClientPrint.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/print/classes/Print.php";

use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\PrintFile;
use Neodynamic\SDK\Web\ClientPrintJob;

// Process request
// Generate ClientPrintJob? only if clientPrint param is in the query string
$urlParts = parse_url($_SERVER['REQUEST_URI']);
$rawQuery = $urlParts['query'];


if (isset($rawQuery)) {
    if ($rawQuery[WebClientPrint::CLIENT_PRINT_JOB]) {
        parse_str($rawQuery, $qs);
        $useDefaultPrinter = ($qs['useDefaultPrinter'] === 'checked');
        $printerName = urldecode($qs['printerName']);
        $pr = new Printer();

        $students = $pr->get_print_job();
        $students_arr = explode(",", $students);

        if (count($students_arr) > 0) {
            foreach ($students_arr as $studentid) {
                $cert_path = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/certificates/$studentid/certificate.jpg";
                $fileName = uniqid() . '.' . $cert_path;
                $filePath = $cert_path;
                if (!Utils::isNullOrEmptyString($filePath)) {
                    //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
                    $cpj = new ClientPrintJob();
                    $cpj->printFile = new PrintFile($filePath, $fileName, null);
                    if ($useDefaultPrinter || $printerName === 'null') {
                        $cpj->clientPrinter = new DefaultPrinter();
                    }  // end if $useDefaultPrinter || $printerName === 'null'
                    else {
                        $cpj->clientPrinter = new InstalledPrinter($printerName);
                    } // end else
                    //Send ClientPrintJob back to the client
                    ob_clean();
                    echo $cpj->sendToClient();
                } // end if !Utils::isNullOrEmptyString($filePath)
            } // end foreach
        } // end if count($students_arr)>0

        /*
          $fileName = uniqid() . '.' . $qs['filetype'];
          $filePath = 'files/penguins300dpi.jpg';
          if (!Utils::isNullOrEmptyString($filePath)) {
          //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
          $cpj = new ClientPrintJob();
          $cpj->printFile = new PrintFile($filePath, $fileName, null);
          if ($useDefaultPrinter || $printerName === 'null') {
          $cpj->clientPrinter = new DefaultPrinter();
          } // end if $useDefaultPrinter || $printerName === 'null'
          else {
          $cpj->clientPrinter = new InstalledPrinter($printerName);
          }
          //Send ClientPrintJob back to the client
          ob_clean();
          echo $cpj->sendToClient();
          } // end if !Utils::isNullOrEmptyString($filePath)
         */
    } // end if $rawQuery[WebClientPrint::CLIENT_PRINT_JOB]
} // end if isset($rawQuery)
    


 