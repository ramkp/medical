<?php

require_once './classes/Cards.php';
$c = new Cards();
$list = $c->get_production_token();
echo $list;

