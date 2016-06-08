<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$list=$sch->get_students_box () ;
echo $list;