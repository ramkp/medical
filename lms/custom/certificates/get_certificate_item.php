<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$page = $_POST['id'];
$list = $cert->get_certificate_item($page);
echo $list;
