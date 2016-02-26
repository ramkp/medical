<?php

require_once './classes/Schedule.php';
$courseid=$_POST['courseid'];
$schedule=new Schedule();
$list=$schedule->get_item_detail_page($courseid, false, false);
echo $list;

