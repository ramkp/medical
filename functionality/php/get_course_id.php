<?php

require_once './classes/Register.php';
$rg = new Register();
$course_name = $_POST['course_name'];
if (trim($course_name) != 'Program') {
    $list = $rg->get_course_id($course_name);
    echo $list;
}
