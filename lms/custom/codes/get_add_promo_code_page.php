<?php

require_once './classes/Codes.php';
$c = new Codes();
$list = $c->get_add_promo_code_page();
echo $list;
