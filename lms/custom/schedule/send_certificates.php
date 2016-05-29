<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$courseid = $_POST['courseid'];
$students = $_POST['students'];
$list = $sch->send_certificate($courseid, $students);
echo $list;
