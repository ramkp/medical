<?php

require_once './classes/Report.php';
$report=new Report();
$stateid=$_POST['stateid'];
$list=$report->get_workshop_by_state($stateid);
echo $list;