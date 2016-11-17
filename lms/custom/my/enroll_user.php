<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$userid = $_POST['userid'];
$courseid = $_POST['courseid'];
$ds->assign_roles($userid, $courseid);
