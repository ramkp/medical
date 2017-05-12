<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$payment = $_POST['payment'];
$list = $ds->refund_braintree_payment(json_decode($payment));
echo $list;