<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$courseid = $_POST['courseid'];
$students = $_POST['students'];
$list = $sch->change_students_course_status($courseid, $students);
echo $list;

