<?php

require_once './classes/Invoice.php';
$invoice=new Invoices();
$list=$invoice->get_send_invoice_page();
echo $list;
