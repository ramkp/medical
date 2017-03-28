<?php

require_once './classes/Codes.php';
$c = new Codes();
$list = $c->get_promotion_codes_page();
echo $list;
