<?php

require_once './classes/Payment.php';
$p = new Payment();
$courseid = $_POST['courseid'];
$slotid = $_POST['slotid'];
$list = $p->get_course_data($courseid, $slotid);
echo $list;
