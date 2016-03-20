<?php

require_once './classes/Payments.php';
$payment=new Payments();
$page=$_POST['id'];
$list=$payment->get_payment_item($page);
echo $list;

