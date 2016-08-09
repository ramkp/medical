<?php

function prepareExpirationDate($exp_date) {
    // MMYY - format
    $mm = substr($exp_date, 0, 2);
    $yy = substr($exp_date, 4);
    $date = $mm . $yy;
    return $date;
}

$exp_date = "062018";
$date = prepareExpirationDate($exp_date);
echo "Date: " . $date . "<br>";
