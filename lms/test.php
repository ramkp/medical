<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';


$time='02/25/2016';
$unix_time=  strtotime($time); 

echo "Human time: ".$time."<br>";
echo "Unix time: ".$unix_time."<br>";
