<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$certs = $_POST['certs'];
$start = $_POST['start'];
$end = $_POST['end'];
$list = $cert->recertificate($certs, $start, $end);
echo $list;
