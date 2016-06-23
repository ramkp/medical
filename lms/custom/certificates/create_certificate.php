<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$courseid = $_POST['courseid'];
$userid = $_POST['userid'];
$start = $_POST['start'];
$end = $_POST['end'];
$list = $cert->create_certificate($courseid, $userid, $start, $end);
echo $list;

