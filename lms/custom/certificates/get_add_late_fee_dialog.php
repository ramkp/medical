<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$list = $cert->get_add_late_fee_dialog();
echo $list;
