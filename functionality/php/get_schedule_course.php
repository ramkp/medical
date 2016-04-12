<?php

require_once './classes/Schedule.php';
$sch=new Schedule();
$courseid=$_POST['courseid'];
$list=$sch->get_course_schedule($courseid);
echo $list;
