<?php

require_once './classes/Certificates.php';
$courseid=$_POST['courseid'];
$userid=$_POST['userid'];
$certificate=new Certificates();
$list=$certificate->send_certificate($courseid, $userid);
echo $list;
