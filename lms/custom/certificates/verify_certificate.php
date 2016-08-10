<?php

require_once './classes/Certificates.php';
$certificate = new Certificates();
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$list = $certificate->verify_certificate($fname, $lname);
echo $list;
