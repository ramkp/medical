<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$students = $_POST['students'];
$scheduler = $_POST['scheduler'];
$list = $sch->get_workshops_list($students, $scheduler);
echo $list;
