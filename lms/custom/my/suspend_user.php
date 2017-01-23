<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$userid = $_POST['userid'];
$state = $_POST['state'];
$ds->suspend_user($userid, $state);
