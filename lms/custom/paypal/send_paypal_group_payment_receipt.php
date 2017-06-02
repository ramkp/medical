<?php

require_once './classes/Cards.php';
$c = new Cards();
$t = $_POST['t'];
$list = $c->add_group_paypal_payment(json_decode($t));
echo $list;




