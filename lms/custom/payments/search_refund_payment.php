<?php

require_once './classes/Payments.php';
$item = $_POST['item'];
$payment = new Payments(0);
$list = $payment->search_refund_payment($item);
echo $list;
