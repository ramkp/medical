<?php

require_once './classes/Register.php';
$rg=new Register();
$list=$rg->get_course_categories();
echo $list;
