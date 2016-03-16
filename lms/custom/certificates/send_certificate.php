<?php

require_once './classes/Certificates.php';
$courseid=$_POST['courseid'];
$userid=$_POST['userid'];
$completion_date=$_POST['completion_date'];
$certificate=new Certificates();
$list=$certificate->send_certificate($courseid, $userid, $completion_date);
echo $list;
