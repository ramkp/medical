<?php

require_once './classes/Partial.php';
$partial = new Partial();
$courseid = $_POST['courseid'];
$userid = $_POST['userid'];
$sum = $_POST['sum'];
$source = $_POST['source'];
$slotid = $_POST['slotid'];
$period = $_POST['period'];
$list = $partial->add_partial_payment($courseid, $userid, $sum, $source, $slotid, $period);
echo $list;

