<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$list = $cert->get_create_box();
echo $list;
