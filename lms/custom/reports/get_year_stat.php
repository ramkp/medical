<?php

require_once 'classes/Report.php';
$report = new Report();
$year1 = $_POST['year1'];
$year2 = $_POST['year2'];
$list = $report->get_year_stat_payments($year1, $year2);
echo $list;

