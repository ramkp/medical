<?php

require_once './classes/Partial.php';
$partial = new Partial();
$courseid = $_POST['courseid'];
$userid = $_POST['userid'];
$sum = $_POST['sum'];
$ptype = $_POST['ptype'];
$slotid = $_POST['slotid'];
$list = $partial->get_payment_section($courseid, $userid, $sum, $ptype, $slotid);
echo $list;
