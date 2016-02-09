<?php

require_once './classes/Certificates.php';
$cert=new Certificates();
$list=$cert->get_certificate_verification_form();
echo $list;
