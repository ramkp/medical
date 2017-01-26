<?php

require_once './classes/Deposit.php';
$d = new Deposit();
$page = $_POST['id'];
$list = $d->get_deposit_item($page);
echo $list;

