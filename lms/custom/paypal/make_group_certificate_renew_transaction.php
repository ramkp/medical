<?php

require_once './classes/Cards.php';
$c = new Cards();
$trans = $_POST['trans'];
$list = $c->renew_group_certificates(json_decode($trans));
echo $list;
