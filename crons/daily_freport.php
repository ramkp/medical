<?php

require_once '/home/cnausa/public_html/crons/classes/Students.php';
$student = new Students();
$type=1; // daily
$list = $student->get_report_payments($type);
echo $list;
