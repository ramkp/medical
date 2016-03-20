<?php

require_once './classes/Invoice.php';
$invoice = new Invoices();
$id = $_POST['id'];
$payment_type = $_POST['payment_type'];
$list = $invoice->make_invoice_paid($id, $payment_type);
echo $list;
