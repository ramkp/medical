<?php

require_once './classes/Promotion.php';
$p = new Promotion();
$search = $_POST['search'];
$list = $p->get_promotion_users(json_decode($search));
echo $list;

