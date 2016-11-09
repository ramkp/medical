<?php

require_once './classes/Register.php';
$rg = new Register();
$courseid = $_POST['courseid'];
$slotid = $_POST['slotid'];
$list = $rg->get_register_course_cities($courseid, $slotid, true);
echo $list;
