<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$userid = $_POST['userid'];
$list = $ds->get_payment_report($userid);
echo $list;
