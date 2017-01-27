<?php

require_once './classes/Deposit.php';
$d = new Deposit();
$dep = $_POST['dep'];
$d->add_deposit(json_decode($dep));
