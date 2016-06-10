<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$scheduler=$_POST['scheduler'];
$start = $_POST['start'];
$end = $_POST['end'];
$sesskey=$_POST['sesskey'];
$list = $sch->get_slots_by_date($scheduler,$start, $end, $sesskey);
echo $list;

