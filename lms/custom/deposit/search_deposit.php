<?php

require_once './classes/Deposit.php';
$d = new Deposit();
$date = $_POST['date'];
$list = $d->search_deposit(json_decode($date));
echo $list;
