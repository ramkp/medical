<?php

require_once './classes/Campus.php';
$c = new Campus();
$list = $c->get_add_dialog();
echo $list;
