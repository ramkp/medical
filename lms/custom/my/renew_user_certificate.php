<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$cert = $_POST['cert'];
$list = $ds->renew_user_certificate(json_decode($cert));
echo $list;

