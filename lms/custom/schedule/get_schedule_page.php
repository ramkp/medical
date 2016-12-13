<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$list = $sch->get_schedule_page();
echo $list;
