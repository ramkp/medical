<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$lsotid = $_POST['slotid'];
$userid = $_POST['userid'];
$list = $sch->add_user_to_slot($lsotid, $userid);
echo $list;
