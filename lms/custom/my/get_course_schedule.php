<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$courseid = $_POST['courseid'];
$list = $ds->is_course_has_schedule($courseid);
echo $list;
