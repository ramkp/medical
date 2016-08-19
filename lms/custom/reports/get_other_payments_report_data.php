<?php

require_once './classes/Report.php';
$report = new Report();
$courseid = $_POST['courseid'];
$from = $_POST['from'];
$to = $_POST['to'];
$type = $_POST['type'];
$list = $report->get_other_payment_report_data($courseid, $from, $to, $type);
echo $list;
