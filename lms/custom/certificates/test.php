<?php

require_once './classes/Certificates.php';
$certObj = new Certificates();
$cert = new stdClass();
$cert->courseid = 41;
$cert->userid = 11773;
$issue = time();
$expire = time();
$certObj->create_new_certificate($cert, $issue, $expire);
echo "<br><br><br><p align='center'>Certficate for User ID: $cert->userid and Course ID: $cert->courseid was created</p>";


