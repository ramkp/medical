<?php

require_once './classes/Cards.php';
$c = new Cards();
$trans = $_POST['trans'];
$list = $c->make_group_registration_payment(json_decode($trans));
echo $list;
