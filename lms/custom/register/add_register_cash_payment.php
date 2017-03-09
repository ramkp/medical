<?php

require_once './classes/Register.php';
$r = new Register();
$user = $_POST['user'];
$list = $r->add_register_cash_payment(json_decode($user));
echo $list;

