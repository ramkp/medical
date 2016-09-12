<?php

require_once './classes/Invoice.php';
$invoice = new Invoices ();
$invoice_id = $_POST['invoice_id'];
$type = $_POST['type'];
$users_list = $_POST['users_list'];
$list = $invoice->attach_any_invoice_payment($invoice_id, $type, $users_list);




