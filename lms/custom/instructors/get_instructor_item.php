<?php

require_once './classes/Instructors.php';
$in = new Instructors();
$page = $_POST['id'];
$list = $in->get_instructor_item($page);
echo $list;