<?php

require_once './classes/Payment.php';
$signup = new Payment();
$user = $_POST['user'];
$list = $signup->internal_signup(json_decode($user));
echo $list;
