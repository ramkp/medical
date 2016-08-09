<?php

require_once './classes/Payments.php';
$payments = new Payments(0);
$courseid = $_POST['id'];
$list = $payments->get_course_payments($courseid);
echo $list;
