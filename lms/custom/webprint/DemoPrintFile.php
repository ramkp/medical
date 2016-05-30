<?php
ob_start();
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/webprint/WebClientPrint.php';

use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;

$title = 'WebClientPrint 2.0 for PHP - Print File Demo';

?>

<input type="hidden" id="sid" name="sid" value="<?php echo session_id(); ?>" />

<div class="container">

    <div class="row">
        <div class="span9">

            <p>
                With <strong>WebClientPrint for PHP</strong> 
            </p>
            <div class="accordion" id="accordion1">                
                <div class="accordion-group" >

                    <div id="collapse2" class="accordion-body ">
                        <div class="accordion-inner">

                            <div class="row">
                                <div class="span4">
                                    <hr />
                                    <label class="checkbox">
                                        <input type="checkbox" id="useDefaultPrinter" /> <strong>Print to Default printer</strong> or... 
                                    </label>
                                    <div id="loadPrinters">
                                        Click to load and select one of the installed printers!
                                        <br />
                                        <a onclick="javascript:jsWebClientPrint.getPrinters();" class="btn btn-success">Load installed printers...</a>

                                        <br /><br />
                                    </div>
                                    <div id="installedPrinters" style="visibility:hidden">
                                        <label for="installedPrinterName">Select an installed Printer:</label>
                                        <select name="installedPrinterName" id="installedPrinterName"></select>
                                    </div>

                                    <script type="text/javascript">
                                        var wcppGetPrintersDelay_ms = 5000; //5 sec

                                        function wcpGetPrintersOnSuccess() {
                                            if (arguments[0].length > 0) {
                                                var p = arguments[0].split("|");
                                                var options = '';
                                                for (var i = 0; i < p.length; i++) {
                                                    options += '<option>' + p[i] + '</option>';
                                                }
                                                $('#installedPrinters').css('visibility', 'visible');
                                                $('#installedPrinterName').html(options);
                                                $('#installedPrinterName').focus();
                                                $('#loadPrinters').hide();
                                            } else {
                                                alert("No printers are installed in your system.");
                                            }
                                        }

                                        function wcpGetPrintersOnFailure() {
                                            alert("No printers are installed in your system.");
                                        }
                                    </script>


                                </div>
                                <div class="span4">
                                    <hr />
                                    <div id="fileToPrint">                                        
                                        <br />
                                        <!--<a class="btn btn-info btn-large" onclick="javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '&filetype=' + $('#ddlFileType').val());">Print File...</a>-->
                                        <a class="btn btn-info btn-large" onclick="javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '&filetype=JPG');">Print File...</a>
                                        <!--<a class="btn btn-info btn-large" onclick="javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '&filetype='+<?php echo $students; ?>);">Print File...</a>-->

                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_contents();
ob_clean();
?>    


<?php
$currentFileName = basename($_SERVER['PHP_SELF']);
$currentFolder = substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strlen($currentFileName));
//echo "Current folder: ".$currentFolder."<br>";
//Specify the ABSOLUTE URL to the php file that will create the ClientPrintJob object
echo WebClientPrint::createScript(Utils::getRoot() . $currentFolder . 'DemoPrintFileProcess.php')
?>

<script type="text/javascript">

    $("#ddlFileType").change(function () {
        var s = $("#ddlFileType option:selected").text();
        if (s == 'DOC')
            $("#ifPreview").attr("src", "http://docs.google.com/gview?url=http://webclientprintphp.azurewebsites.net/files/LoremIpsum.doc&embedded=true");
        if (s == 'PDF')
            $("#ifPreview").attr("src", "http://docs.google.com/gview?url=http://webclientprintphp.azurewebsites.net/files/LoremIpsum.pdf&embedded=true");
        if (s == 'TXT')
            $("#ifPreview").attr("src", "http://docs.google.com/gview?url=http://webclientprintphp.azurewebsites.net/files/LoremIpsum.txt&embedded=true");
        if (s == 'TIF')
            $("#ifPreview").attr("src", "http://docs.google.com/gview?url=http://webclientprintphp.azurewebsites.net/files/patent2pages.tif&embedded=true");
        if (s == 'XLS')
            $("#ifPreview").attr("src", "http://docs.google.com/gview?url=http://webclientprintphp.azurewebsites.net/files/SampleSheet.xls&embedded=true");
        if (s == 'JPG')
            $("#ifPreview").attr("src", "http://webclientprintphp.azurewebsites.net/files/penguins300dpi.jpg");
        if (s == 'PNG')
            $("#ifPreview").attr("src", "http://webclientprintphp.azurewebsites.net/files/SamplePngImage.png");
    }).change();

</script>

<?php
$script = ob_get_contents();
ob_clean();


include("template.php");
?>

