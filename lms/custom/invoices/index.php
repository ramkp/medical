<?php

require_once './classes/Invoice.php';
$invoice=new Invoices();
$list=$invoice->get_invoice_crednetials();
echo $list;
