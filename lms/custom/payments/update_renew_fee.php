<?php

require_once './classes/Payments.php';
$payment=new Payments();
$fee=$_POST['fee'];
$list=$payment->update_renew_fee($fee);
echo $list;
