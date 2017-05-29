<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$coursename = $_POST['coursename'];
$list = $ds->get_course_category_by_name($coursename);
echo $list;
