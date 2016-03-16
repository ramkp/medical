<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$end = $_POST['id'];
$list = $cert->get_certificate_item($end);
echo $list;
