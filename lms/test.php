<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

/*
$time='02/10/2017';
$unix_time=  strtotime($time); 

echo "Human time: ".$time."<br>";
echo "Unix time: ".$unix_time."<br>";
*/

$unixtime='1477333729';
$htime=date('m/d/Y', $unixtime);

echo "Unix time: ".$unixtime."<br>";
echo "Human time: ".$htime."<br>";