<?php

require_once './classes/Certificates.php';
$exp_date=1469534400;
$new_exp=$exp_date+31104000+432000;
echo "Original Expiration date unixtime: ".$exp_date."<br>";
echo "New expiration date unixtime: ".$new_exp."<br>";

echo "Original Expiration date: ".date('m-d-Y', $exp_date)."<br>";
echo "New Expiration date: ".date('m-d-Y', $new_exp)."<br>";