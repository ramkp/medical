<?php

require_once './classes/Instructors.php';
$in = new Instructors();
$list = $in->get_instructors_page();
echo $list;
