<?php

require_once './classes/Groups.php';
$g = new Groups();
$cert = $_POST['cert'];
$list = $g->send_paypal_group_renew_receipt(json_decode($cert));
echo $list;
