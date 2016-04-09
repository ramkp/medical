<?php

require_once './classes/Schedule.php';
$schedule = new Schedule();
$stateid = $_POST['stateid'];
$list = $schedule->get_state_programs($stateid);
echo $list;
