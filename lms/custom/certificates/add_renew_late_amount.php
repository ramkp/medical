<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$fee = $_POST['fee'];
$list = $cert->add_renew_late_fee(json_decode($fee));
echo $list;
