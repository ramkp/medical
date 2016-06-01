<?php


require_once './classes/Course.php';
$c = new Course();
$list = $c->process_course_compleations();
echo $list;
