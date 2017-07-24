<?php

require_once './classes/Cards.php';
$c = new Cards();
$transaction = $_POST['trans'];
$list = $c->create_transaction_test(json_decode($transaction));
echo $list;
