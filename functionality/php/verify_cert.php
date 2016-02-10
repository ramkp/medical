<?php

require_once './classes/Certificates.php';
$cert=new Certificates();
$user_fio=$_POST['user_fio'];
$user_cert_no=$_POST['user_cert_no'];
$list=$cert->verify_certificate($user_fio, $user_cert_no);
