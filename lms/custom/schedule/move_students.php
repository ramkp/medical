<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$users = $_POST['users'];
$slotid = $_POST['slotid'];
$schedulerid = $_POST['schedulerid'];
$list = $sch->move_students($slotid, $users, $schedulerid);
echo $list;
