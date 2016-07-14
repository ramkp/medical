<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$students = $_POST['students'];
$courseid = $_POST['courseid'];
$list = $sch->print_certificate($courseid, $students);
echo $list;
