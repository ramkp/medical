<?php

require_once './classes/Certificates.php';
$certificate=new Certificates();
$list=$certificate->get_certificates_list();
echo $list;