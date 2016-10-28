<?php

require_once './classes/Report.php';
$report = new Report();
$courseid = $_POST['courseid'];
$from = $_POST['from'];
$to = $_POST['to'];
$list = $report->get_revenue_report_data($courseid, $from, $to, false);
echo $list;
?>

