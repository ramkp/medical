<?php

require_once './classes/Report.php';
$report=new Report();
$courseid=$_POST['courseid'];
$from=$_POST['from'];
$to=$_POST['to'];
$list=$report->program_export($courseid, $from, $to);
echo $list;

