<?php

require_once './classes/Report.php';
$report = new Report();
$list = $report->get_pemissions_page();
echo $list;
