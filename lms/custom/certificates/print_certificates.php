<?php

require_once './classes/Certificates.php';
$cert=new Certificates();
$students=$_POST['certs'];
$cert->print_certificates($students);

?>

