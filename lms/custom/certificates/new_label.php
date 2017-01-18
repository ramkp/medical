<?php

require_once './classes/Certificates.php';
$c = new Certificates();
$courseid = 45;
$userid = 5273;
$c->create_label($courseid, $userid);
echo "Label has been created ....<br>";
$link = "<a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/custom/certificates/$userid/label.pdf'>View</a>";
echo "<br>----------------------------------------------------------<br>";
echo "View label here: $link";



