<?php

require_once './classes/Instructors.php';
$in = new Instructors();
$userid = $_POST['userid'];
$list = $in->get_settings_dialog($userid);
echo $list;
