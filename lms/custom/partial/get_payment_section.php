<?php

require_once './classes/Partial.php';
$partial = new Partial();
$courseid=$_POST['courseid'];
$userid=$_POST['userid'];
$sum=$_POST['sum'];
$ptype = $_POST['ptype'];
$list = $partial->get_payment_section($courseid, $userid, $sum, $ptype);
echo $list;
