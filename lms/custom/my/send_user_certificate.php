<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$cert = $_POST['cert'];
$list = $ds->send_user_cetificate(json_decode($cert));
echo $list;
