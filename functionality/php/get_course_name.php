<?php

require_once './classes/Payment.php';
$p = new Payment();
$courseid = $_POST['id'];
$list = $p->get_course_name($courseid);
echo $list;
