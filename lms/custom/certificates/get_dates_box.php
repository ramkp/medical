<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$certs = $_POST['certs'];
//echo "Get date box cetrificates: ".$certs."<br>" ;
$list = $cert->get_dates_box($certs);
echo $list;

