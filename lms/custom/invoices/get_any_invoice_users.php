<?php

require_once './classes/Invoice.php';
$invoice = new Invoices ();
$id = $_POST ['id'];
$list = $invoice->get_any_invoice_users($id);
echo $list;
