<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$courseid=$_POST['courseid'];
$userid=$_POST['userid'];
$list=$cert->print_certificate($courseid, $userid);
echo $list;