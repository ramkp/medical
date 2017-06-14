<?php

require_once './classes/Cards.php';
$c = new Cards();
$trans = $_POST['trans'];
$list = $c->get_renew_receipt(json_decode($trans));
echo $list;
