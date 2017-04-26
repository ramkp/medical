<?php

require_once './classes/Certificates2.php';
$c2 = new Certificates2();
$courseid = $_REQUEST['courseid'];
$userid = $_REQUEST['userid'];
$period = $_REQUEST['period'];
if ($courseid > 0 && $userid > 0 && $period > 0) {
    $c2->renew_certificate($courseid, $userid, $period);
} // end if
else {
    echo "<br><p style='text-align:center;font-weight:bold;'>Wrong input params</p>";
} // end else
