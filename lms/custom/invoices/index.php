<?php

require_once './classes/Invoice.php';
$invoice=new Invoice();
$list=$invoice->get_invoice_crednetials();
echo $list;
