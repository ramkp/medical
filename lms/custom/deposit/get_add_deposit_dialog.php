<?php

require_once './classes/Deposit.php';
$d = new Deposit();
$list = $d->get_add_deposit_dialilog();
echo $list;

