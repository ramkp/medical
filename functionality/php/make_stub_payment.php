<?php

header('Access-Control-Allow-Origin: *');
require_once './classes/Payment.php';
$signup = new Payment();
$card = $_POST['card'];
$list = $signup->make_stub_payment(json_decode($card));
echo $list;
