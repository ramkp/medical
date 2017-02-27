<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$userid = 13361;
$ds->get_payment_report($userid);
