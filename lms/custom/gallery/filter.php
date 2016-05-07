<?php

require_once './classes/Gallery.php';
$gl = new Gallery();
$state = $_POST['state'];
$month = $_POST['month'];
$year = $_POST['year'];

$state = ($state == 0) ? null : $state;
$month = ($month == 0) ? null : $month;
$year = ($year == 0) ? null : $year;
$list = $gl->filter($state, $month, $year);
echo $list;



