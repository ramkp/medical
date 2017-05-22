<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$payment = $_POST['payment'];
$list = $ds->make_authorize_refund(json_decode($payment));
echo $list;
