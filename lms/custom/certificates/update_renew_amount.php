<?php

require_once './classes/Certificates.php';
$certObj = new Certificates();
$cert = $_POST['cert'];
$list = $certObj->update_renew_amount(json_decode($cert));
echo $list;
