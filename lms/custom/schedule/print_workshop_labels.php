<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$courseid = $_POST['courseid'];
$students = $_POST['students'];
$list = $sch->print_certificate_labels($courseid, $students);
echo $list;

