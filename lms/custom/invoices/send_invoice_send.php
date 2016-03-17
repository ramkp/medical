<?php

require_once './classes/Invoice.php';
$invoice=new Invoices();
$courseid=$_POST['courseid'];
$userid=$_POST['userid'];

$list=$invoice->send_invoice($courseid, $userid);
echo $list;
