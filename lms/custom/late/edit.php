<?php

require_once './classes/Late.php';
$late = new Late();
$period = $_POST['period'];
$amount = $_POST['amount'];
$courseid = $_POST['courseid'];
$list = $late->save_changes($period, $amount, $courseid);
echo $list;
