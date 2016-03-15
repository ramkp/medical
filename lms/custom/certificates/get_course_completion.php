<?php

require_once './classes/Certificates.php';
$userid=$_POST['userid'];
$courseid=$_POST['courseid'];
$certificate=new Certificates();
$list=$certificate->get_course_completion($courseid, $userid);
echo $list;