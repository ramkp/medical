<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$courseid = $_POST['courseid'];
$students = $_POST['students'];
$list = $sch->check_students_balance($courseid, $students);
echo $list;
