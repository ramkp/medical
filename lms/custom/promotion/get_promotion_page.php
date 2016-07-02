<?php

require_once './classes/Promotion.php';
$pm = new Promotion();
$list = $pm->get_promotion_page();
echo $list;
