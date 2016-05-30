<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$students = $_POST['students'];
$courseid = $_POST['courseid'];
$sch->print_certificate($courseid, $students);
