<?php
require_once './classes/Invoice.php';
$invoice = new Invoices ();
$id = $_POST ['id'];
$type = $_POST ['type'];
$list = $invoice->get_any_invoice_dilog ($id, $type);
echo $list; 