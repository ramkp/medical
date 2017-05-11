<?php

require_once './classes/Cards.php';
$c = new Cards();
$transaction = $_POST['trans'];
$list = $c->create_any_pay_transaction(json_decode($transaction));
echo $list;
