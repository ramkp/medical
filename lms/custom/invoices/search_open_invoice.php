<?php

require_once './classes/Invoice.php';
$invoice = new Invoices();
$item = $_POST['item'];
$list = $invoice->search_invoice($item, false);
echo $list;
