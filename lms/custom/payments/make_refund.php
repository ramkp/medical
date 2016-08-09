<?php

require_once './classes/Payments.php';
$payments = new Payments(0);
$paymentid = $_POST['paymentid'];
$list = $payments->make_refund($paymentid);
echo $list;
