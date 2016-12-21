<?php

require_once './classes/Promotion.php';
$pr = new Promotion();
$list = $pr->get_user_states();
echo $list;


