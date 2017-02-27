<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$userid = $_POST['userid'];
$list = $ds->create_attendance_report($userid);
echo $list;
