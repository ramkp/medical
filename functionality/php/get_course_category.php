<?php

require_once './classes/Register.php';
$r = new Register();
$courseid = $_POST['courseid'];
$list = $r->get_course_category($courseid);
echo $list;
