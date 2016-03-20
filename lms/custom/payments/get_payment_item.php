<?php

require_once './classes/Payments.php';
$payment=new Payments();
$page=$_POST['id'];
$payment_type=$_POST['payments_type'];
$list=$payment->get_payment_item($page, $payment_type);
echo $list;

