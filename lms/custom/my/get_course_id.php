<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$coursename = $_POST['coursename'];
$wsname = $_POST['wsname'];
$list = $ds->get_courseid_by_name($coursename, $wsname);
echo $list;
