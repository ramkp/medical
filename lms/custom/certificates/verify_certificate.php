<?php

require_once './classes/Certificates.php';
$certificate=new Certificates();
$cert_fio=$_POST['cert_fio'];
$cert_no=$_POST['cert_no'];
$list=$certificate->verify_certificate($cert_fio, $cert_no);
echo $list;
