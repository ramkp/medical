<?php 
  ob_start();
  session_start();
  
  include 'WebClientPrint.php';
  use Neodynamic\SDK\Web\WebClientPrint;
  use Neodynamic\SDK\Web\Utils;

  $title = 'WebClientPrint 2.0 for PHP - Print Commands Demo';
  
  $head = '<link href="content/formToWizard2.css" rel="stylesheet" />';
  
?>

<h3>Print Raw/Text Commands</h3>

<form id="myForm" action="">

    <input type="hidden" id="sid" name="sid" value="<?php echo session_id(); ?>" />
        
        <fieldset>
            <legend>Client Printer Settings</legend>
            
            <div>
                WebClientPrint does support all common printer communications like USB-Installed 
                Drivers, Network/IP-Ethernet, Serial COM-RS232 and Parallel (LPT).  
                <br />
                <br />
                I want to:&nbsp;&nbsp;
                <select id="pid" name="pid">
                  <option selected="selected" value="0">Use Default Printer</option>
                  <option value="1">Display a Printer dialog</option>
                  <option value="2">Use an installed Printer</option>
                  <option value="3">Use an IP/Etherner Printer</option>
                  <option value="4">Use a LPT port</option>
                  <option value="5">Use a RS232 (COM) port</option>
                </select>
                <br />
                <br />
                <div id="info" class="alert alert-info" style="font-size:11px;"></div>                
                <br />
            </div>
            
            <div id="installedPrinter">
                <div id="loadPrinters" name="loadPrinters">
                WebClientPrint can detect the installed printers in your machine. <a onclick="javascript:jsWebClientPrint.getPrinters();" class="btn btn-success">Load installed printers...</a>
                <br /><br />
                </div>
                <label for="installedPrinterName">Select an installed Printer:</label>
                <select name="installedPrinterName" id="installedPrinterName"></select>

            
                <script type="text/javascript">
                var wcppGetPrintersDelay_ms = 5000; //5 sec

                function wcpGetPrintersOnSuccess(){
                    if(arguments[0].length > 0){
                        var p=arguments[0].split("|");
                        var options = '';
                        for (var i = 0; i < p.length; i++) {
                            options += '<option>' + p[i] + '</option>';
                        }
                        $('#installedPrinterName').html(options);
                        $('#installedPrinterName').focus();
                        $('#loadPrinters').hide();
                    }else{
                        alert("No printers are installed in your system.");
                    }
                }

                function wcpGetPrintersOnFailure() {
                    alert("No printers are installed in your system.");
                }
                </script>

            </div>           
            
            <div id="netPrinter">
                <label for="netPrinterHost">Printer's DNS Name:</label>
                <input type="text" name="netPrinterHost" id="netPrinterHost" />
                <label for="netPrinterIP">or IP Address:</label>
                <input type="text" name="netPrinterIP" id="netPrinterIP" />
                <label for="netPrinterPort">Printer's Port:</label>
                <input type="text" name="netPrinterPort" id="netPrinterPort" />
            </div>

            <div id="parallelPrinter">
                <label for="parallelPort">Parallel Port:</label>
                <input type="text" name="parallelPort" id="parallelPort" value="LPT1" />
            </div>

            <div id="serialPrinter">
            <table border="0">
                <tr>
                    <td valign="top">
                        <label for="serialPort">Serial Port:</label>
                        <input type="text" name="serialPort" id="serialPort" value="COM1" />
                        <label for="serialPortBauds">Baud Rate:</label>
                        <input type="text" name="serialPortBauds" id="serialPortBauds" value="9600" />
                        <label for="serialPortDataBits">Data Bits:</label>
                        <input type="text" name="serialPortDataBits" id="serialPortDataBits" value="8" />
                    </td>
                    <td style="width:30px;"></td>
                    <td valign="top">
                        <label for="serialPortParity">Parity:</label>
                        <select id="serialPortParity" name="serialPortParity">
                            <option value="0" selected="selected">None</option>
                            <option value="1">Odd</option>
                            <option value="2">Even</option>
                            <option value="3">Mark</option>
                            <option value="4">Space</option>
                        </select>

                        <label for="serialPortStopBits">Stop Bits:</label>
                        <select id="serialPortStopBits" name="serialPortStopBits">
                            <option value="1" selected="selected">One</option>
                            <option value="2">Two</option>
                            <option value="3">OnePointFive</option>
                        </select>

                        <label for="serialPortFlowControl">Flow Control:</label>
                        <select id="serialPortFlowControl" name="serialPortFlowControl">
                            <option value="0" selected="selected">None</option>
                            <option value="1">XOnXOff</option>
                            <option value="2">RequestToSend</option>
                            <option value="3">RequestToSendXOnXOff</option>
                        </select>
                    </td>
                </tr>
            </table>
                       
             
            </div>
            
        </fieldset>
        <fieldset>
            <legend>Printer Commands</legend>
            
            <p>
                Enter the printer's commands you want to send and is supported by the specified printer (ESC/P, PCL, ZPL, EPL, DPL, IPL, EZPL, etc). 
                <br /><br />
                <b>NOTE:</b> You can use the <b>hex notation for non-printable characters</b> e.g. for Carriage Return (ASCII Hex 0D) you can specify 0x0D
                
            </p>
            <textarea id="printerCommands" name="printerCommands" rows="10" cols="80" class="span9"></textarea>
            <br /><br />
            <div class="alert alert-info" style="font-size:11px;">
            <b>Upload Files</b><br />
            This online demo does not allow you to upload files. So, if you have a file containing the printer commands like a PRN file, Postscript, PCL, ZPL, etc, then we recommend you to <a href="http://www.neodynamic.com/products/printing/raw-data/php/download/" target="_blank">download WebClientPrint</a> and test it by using the sample source code included in the package. Feel free to <a href="http://www.neodynamic.com/support" target="_blank">contact our Tech Support</a> for further assistance, help, doubts or questions.             
            </div>            
        </fieldset>        
        <fieldset>
            <legend>Ready to print!</legend>
            <h3>Your settings were saved! Now it's time to <a href="#" onclick="javascript:doClientPrint();" class="btn btn-large btn-success">Print</a></h3>           
            <br /><br />
        </fieldset>
        
    </form>
    <br />
    <br />
    <br />

<?php
  $content = ob_get_contents();
  ob_clean();
?>    

<script type="text/javascript" src="scripts/formToWizard2.js"></script>
<script type="text/javascript" src="scripts/DemoPrintCommands.js"></script>    

<?php
  $currentFileName = basename($_SERVER['PHP_SELF']);
  $currentFolder = substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strlen($currentFileName));
  //Specify the ABSOLUTE URL to the php file that will create the ClientPrintJob object
  echo WebClientPrint::createScript(Utils::getRoot().$currentFolder.'DemoPrintCommandsProcess.php')
?>


<?php
  $script = ob_get_contents();
  ob_clean();
  
  
  include("template.php");
?>

