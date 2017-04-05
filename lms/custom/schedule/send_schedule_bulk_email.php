<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$msg = $_POST['msg'];
$sch->send_schedule_bulk_email(json_decode($msg));

