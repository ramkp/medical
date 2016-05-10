<?php

require_once './classes/Payments.php';
//print_r($_POST);
//echo "<br>";
$page=$_POST['id'];
$payment_type=$_POST['payments_type'];
$payment=new Payments($payment_type);
$list=$payment->get_payment_item($page, $payment_type);
echo $list;

