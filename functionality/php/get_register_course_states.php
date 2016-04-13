<?php

require_once './classes/Register.php';
$rg=new Register();
$courseid=$_POST['courseid'];
$list=$rg->get_register_course_states($courseid);
echo $list;