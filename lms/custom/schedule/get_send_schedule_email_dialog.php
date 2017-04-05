<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$userslist = $_POST['userslist'];
$list = $sch->get_send_schedule_email_dialog($userslist);
echo $list;
