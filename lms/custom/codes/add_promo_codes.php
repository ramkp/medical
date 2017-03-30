<?php

require_once './classes/Codes.php';
$c = new Codes();
$code = $_POST['code'];
$list = $c->add_promo_codes(json_decode($code));
echo $list;
