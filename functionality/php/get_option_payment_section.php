<?php

require_once 'classes/Payment.php';
$payment=new Payment();
$option=$_POST['option'];
$list=$payment->get_payment_with_options($option);
echo $list; 

