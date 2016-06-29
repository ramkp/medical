<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$students = $_POST['students'];
$schedulerid = $_POST['schedulerid'];
$list = $sch->remove_students($students, $schedulerid);
echo $list;

