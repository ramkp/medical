<?php

require_once './classes/Certificates.php';
$courseid = 4;
$userid = 24;
$certificate = new Certificates();
$date = 0;
$list = $certificate->prepare_ceriticate($courseid, $userid, $date);
echo $list;
