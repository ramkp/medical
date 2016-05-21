<?php

require_once './classes/Certificates.php';
$certificate=new Certificates();
$item=$_POST['item'];
$list=$certificate->search_certificate(trim($item));
echo $list;
