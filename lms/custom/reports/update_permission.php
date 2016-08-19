<?php

require_once './classes/Report.php';
$report = new Report();
$moduleid = $_POST['moduleid'];
$status = $_POST['status'];
$list = $report->update_permission($moduleid, $status);
echo $list;
