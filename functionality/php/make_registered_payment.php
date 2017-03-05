<?php

require_once './classes/Payment.php';
$p = new Payment();
$card = $_POST['card'];
$list = $p->make_registered_payment(json_decode($card));
echo $list;

