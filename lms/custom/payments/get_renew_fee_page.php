<?php

require_once './classes/Payments.php';
$payment=new Payments();
$list=$payment->get_renew_fee_page();
echo $list;
