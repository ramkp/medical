<?php

require_once './classes/Schedule.php';
$sch=new Schedule();
$state=$_POST['stateid'];
$courseid=$_POST['courseid'];
$list=$sch->get_course_schedule($courseid, $state);
echo $list;

