<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$certs = $_POST['certs'];
$list = $cert->renew_certificates($certs);
echo $list;
