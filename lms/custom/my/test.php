<?php

require_once './classes/Dashboard.php';
$ds=new Dashboard();
$list=$ds->get_courses_questions_banks();
echo $list;
