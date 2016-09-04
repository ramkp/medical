<?php

require_once './classes/Payments.php';
$payments = new Payments(0);
$paymentid = $_POST['paymentid'];
$amount = $_POST['amount'];
$list = $payments->make_refund($paymentid, $amount);
echo $list;
