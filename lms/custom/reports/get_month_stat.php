<?php

require_once 'classes/Report.php';
$report = new Report();
$year = $_POST['year'];
$month1 = $_POST['month1'];
$month2 = $_POST['month2'];
$list = $report->get_month_stat_payments($year, $month1, $month2);
echo $list;



