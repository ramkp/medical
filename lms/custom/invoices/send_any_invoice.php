<?php
require_once './classes/Invoice.php';
$invoice = new Invoices ();
$courseid = $_POST ['courseid'];
$amount = $_POST ['amount'];
$email = $_POST ['email'];
$list = $invoice->send_any_invoice ( $courseid, $amount, $email );
echo $list; 