<?php

require_once './classes/Invoice.php';
$user=new stdClass();
$user->first_name='First Name';
$user->last_name='Last Name';
$user->email='me@john.com';
$user->courseid=6;
$invoice=new Invoice();

$invoice_num=$invoice->get_personal_invoice($user);
echo "Invoice with num $invoice_num is created ...";