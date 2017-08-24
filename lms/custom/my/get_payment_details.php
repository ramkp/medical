<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$trans_id = $_POST['trans_id'];
$list = $ds->get_payment_details($trans_id);
echo $list;
