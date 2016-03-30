<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$courseid=$_POST['courseid'];
$userid=$_POST['userid'];
$list=$cert->print_label($courseid, $userid);
echo $list;