<?php

require_once './classes/Cards.php';
$c = new Cards();
$list = $c->get_sandbox_token();
echo $list;
