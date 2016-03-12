<?php

require_once './classes/Invoice.php';
$invoice = new Invoice();
$phone = $_POST['phone'];
$fax = $_POST['fax'];
$email = $_POST['email'];
$site = $_POST['site'];
$list = $invoice->update_invoice_crednetials($phone, $fax, $email, $site);
echo $list;
