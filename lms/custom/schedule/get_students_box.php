<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$courseid=$_POST['courseid'];
$scheduler=$_POST['scheduler'];
$list=$sch->get_students_box ($courseid,$scheduler);
echo $list;