<?php

require_once './classes/Invoice.php';
$id=$_POST['id'];
$invoice=new Invoices();
$list=$invoice->get_invoice_course_by_category($id);
echo $list;