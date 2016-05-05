<?php

require_once './classes/Late.php';
$late = new Late();
$period = $_POST['period'];
$amount = $_POST['amount'];
$list = $late->save_changes($period, $amount);
echo $list;
