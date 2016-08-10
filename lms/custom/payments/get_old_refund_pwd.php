<?php

require_once './classes/Payments.php';
$payments = new Payments(0);
$list = $payments->get_old_refund_pwd();
echo $list;
