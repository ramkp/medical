<?php

require_once './classes/Payments.php';
$payments = new Payments(0);
$pwd = $_POST['pwd'];
$list = $payments->update_refund_pwd($pwd);
echo $list;

