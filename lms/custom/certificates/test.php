<?php

require_once './classes/Certificates2.php';
$exp_date = 1469534400;
$new_exp = $exp_date + 31104000 + 432000;
echo "Original Expiration date unixtime: " . $exp_date . "<br>";
echo "New expiration date unixtime: " . $new_exp . "<br>";

echo "Original Expiration date: " . date('m-d-Y', $exp_date) . "<br>";
echo "New Expiration date: " . date('m-d-Y', $new_exp) . "<br>";

$original_date = '02/20/2017';
echo "Original human date: $original_date <br>";
if (strtotime($original_date) === false) {
    echo "Date transformation failure ...<br>";
} // end if
else {
    echo "Corresponded unix timestamp " . strtotime($original_date) . "<br>";
} // end else

echo "<br>---------------------------------------------------------------------------<br>";
$courseid = 57;
$userid = 12937;
$expire_year = 1;

echo "Course ID: " . $courseid . "<br>";
echo "User ID:" . $userid . "<br>";
echo "Prologation period (years): " . $expire_year . "<br>";
$cert = new Certificates2();
$list = $cert->renew_certificate($courseid, $userid, $expire_year);
echo $list;



