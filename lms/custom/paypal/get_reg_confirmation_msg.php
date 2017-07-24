<?php

require_once './classes/Cards.php';
$c = new Cards();
$transaction = $_POST['trans'];
$list = $c->get_user_details(json_decode($transaction));
echo $list;
