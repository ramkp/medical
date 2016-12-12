<?php

require_once './classes/Campus.php';
$c = new Campus();
$list = $c->get_campuses_list();
echo $list;
