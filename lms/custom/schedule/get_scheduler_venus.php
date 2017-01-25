<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$list = $sch->get_scheduler_venus();
echo $list;
