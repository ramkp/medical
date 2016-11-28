<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$fee = $_POST['fee'];
$list = $cert->update_renew_late_amount(json_decode($fee));
echo $list;

