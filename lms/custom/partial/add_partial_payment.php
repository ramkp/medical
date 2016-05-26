<?php

require_once './classes/Partial.php';
$partial = new Partial();
$courseid = $_POST['courseid'];
$userid = $_POST['userid'];
$sum = $_POST['sum'];
$list = $partial->add_partial_payment($courseid, $userid, $sum);
echo $list;

