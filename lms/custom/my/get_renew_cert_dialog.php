<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$cert = $_POST['cert'];
$list = $ds->get_renew_cert_dialog(json_decode($cert));
echo $list;
