<?php

require_once './classes/Deposit.php';
$d = new Deposit();
$deposit = $_POST['deposit'];
$d->update_deposit(json_decode($deposit));
