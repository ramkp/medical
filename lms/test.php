<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

/*
  $time='02/10/2017';
  $unix_time=  strtotime($time);

  echo "Human time: ".$time."<br>";
  echo "Unix time: ".$unix_time."<br>";
 */

$date_string = '09/08/2017 03:38:29';
$date_u_string = strtotime($date_string);
echo "Date string: " . $date_string."<br>";
echo "Date unixtime string: " . $date_u_string . "<br>";

$unixtime = '1477333729';
$htime = date('m/d/Y', $unixtime);

echo "Unix time: " . $unixtime . "<br>";
echo "Human time: " . $htime . "<br>";
