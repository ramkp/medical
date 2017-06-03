<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$msg = $_POST['msg'];
$list = $sch->send_schedule_bulk_sms(json_decode($msg));
echo $list;

