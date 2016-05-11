<?php

session_start();

    include 'WebClientPrint.php';
    use Neodynamic\SDK\Web\WebClientPrint;
    use Neodynamic\SDK\Web\Utils;
    use Neodynamic\SDK\Web\DefaultPrinter;
    use Neodynamic\SDK\Web\InstalledPrinter;
    use Neodynamic\SDK\Web\PrintFile;
    use Neodynamic\SDK\Web\ClientPrintJob;

    // Process request
    // Generate ClientPrintJob? only if clientPrint param is in the query string
    $urlParts = parse_url($_SERVER['REQUEST_URI']);
    
    if (isset($urlParts['query'])){
        $rawQuery = $urlParts['query'];
        parse_str($rawQuery, $qs);
        if(isset($qs[WebClientPrint::CLIENT_PRINT_JOB])){

            //IMPORTANT: For Windows clients, Adobe Reader needs to be installed at the client side
            
            $useDefaultPrinter = ($qs['useDefaultPrinter'] === 'checked');
            $printerName = urldecode($qs['printerName']);

            //the PDF file to be printed, supposed to be in files folder
            $filePath = 'files/LoremIpsum.pdf';
            //create a temp file name for our PDF file...
            $fileName = uniqid().'.pdf';
            
            //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
            $cpj = new ClientPrintJob();
            //Create a PrintFile object with the PDF file
            $cpj->printFile = new PrintFile($filePath, $fileName, null);
            if ($useDefaultPrinter || $printerName === 'null'){
                $cpj->clientPrinter = new DefaultPrinter();
            }else{
                $cpj->clientPrinter = new InstalledPrinter($printerName);
            }

            //Send ClientPrintJob back to the client
            ob_start();
            ob_clean();
            echo $cpj->sendToClient();
            ob_end_flush();
            exit();
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>How to directly Print PDF without Preview or Printer Dialog</title>
</head>
<body>
    <!-- Store User's SessionId -->
    <input type="hidden" id="sid" name="sid" value="<?php echo session_id(); ?>" />
    
    <h1>How to directly Print PDF without Preview or Printer Dialog</h1>
    <label class="checkbox">
        <input type="checkbox" id="useDefaultPrinter" /> <strong>Use default printer</strong> or...
    </label>
    <div id="loadPrinters">
    <br />
    WebClientPrint can detect the installed printers in your machine.
    <br />
    <input type="button" onclick="javascript:jsWebClientPrint.getPrinters();" value="Load installed printers..." />
                    
    <br /><br />
    </div>
    <div id="installedPrinters" style="visibility:hidden">
    <br />
    <label for="installedPrinterName">Select an installed Printer:</label>
    <select name="installedPrinterName" id="installedPrinterName"></select>
    </div>
            
    <br /><br />
    <input type="button" style="font-size:18px" onclick="javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val());" value="Print PDF..." />
        
    <script type="text/javascript">
        var wcppGetPrintersDelay_ms = 5000; //5 sec

        function wcpGetPrintersOnSuccess(){
            // Display client installed printers
            if(arguments[0].length > 0){
                var p=arguments[0].split("|");
                var options = '';
                for (var i = 0; i < p.length; i++) {
                    options += '<option>' + p[i] + '</option>';
                }
                $('#installedPrinters').css('visibility','visible');
                $('#installedPrinterName').html(options);
                $('#installedPrinterName').focus();
                $('#loadPrinters').hide();                                                        
            }else{
                alert("No printers are installed in your system.");
            }
        }

        function wcpGetPrintersOnFailure() {
            // Do something if printers cannot be got from the client
            alert("No printers are installed in your system.");
        }
    </script>
    
    <!-- Add Reference to jQuery at Google CDN -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>

    <?php
    //Specify the ABSOLUTE URL to the php file that will create the ClientPrintJob object
    //In this case, this same page
    echo WebClientPrint::createScript(Utils::getRoot().'/PrintPdfSample/PrintPdf.php')
    ?>
       

</body>
</html>