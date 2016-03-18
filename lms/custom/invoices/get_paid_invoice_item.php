<?php

require_once './classes/Invoice.php';
$invoice = new Invoices();
$page=$_POST['id'];
$list = $invoice->get_paid_invoice_item($page);
echo $list;
