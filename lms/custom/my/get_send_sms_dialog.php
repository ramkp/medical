<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$item = $_POST['item'];
$list = $ds->get_send_sms_dialog(json_decode($item));
echo $list;
