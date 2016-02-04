<?php

require_once './classes/Signup.php';
$signup = new Signup();
$card = $_POST['card'];
$list = $signup->make_stub_payment(json_decode($card));
echo $list;
