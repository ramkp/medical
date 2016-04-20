<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$courseid = $_POST['courseid'];
$slotid = $_POST['slotid'];
$userid=$_POST['userid'];
$list = $ds->enrol_user_to_course($courseid,$slotid,$userid);
echo $list;
