<?php

require_once './classes/Certificates.php';
$id=$_POST['id'];
$certificate=new Certificates();
$list=$certificate->get_course_users2($id);
echo $list;
