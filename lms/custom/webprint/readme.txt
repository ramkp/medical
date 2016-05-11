==========================================================
 WebClientPrint for PHP
 Version 2.0
 Copyright (c) 2003-2016
 by Neodynamic SRL
 All Rights Reserved.

 http://www.neodynamic.com
 

 Support: http://www.neodynamic.com/support
 
==========================================================

WebClientPrint for PHP - Sample Demo

READ ME

This website shows you how to use WebClientPrint for PHP to print
raw commands as well as common file formats from PHP to clients
printers without displaying any dialogs (if needed).

This website is the same available online at 
http://webclientprintphp.azurewebsites.net

However, to test it locally, you must make the following changes:

- Open WebClientPrint.php file and set this line to match your local 
configuration. For example, if the sample demo website local URL is
http://127.0.0.1:8181/WebClientPrint/WebsiteSampleDemo

then you must update the following line in the WebClientPrint.php file
as follows:

//Set ABSOLUTE URL to WebClientPrint.php file
WebClientPrint::$webClientPrintAbsoluteUrl = Utils::getRoot().'/WebClientPrint/WebsiteSampleDemo/WebClientPrint.php';


