<?php

require_once './classes/Promotion.php';
$pr = new Promotion();
$userslist = $_POST['userslist'];
$list = $pr->get_add_campaign_dialog($userslist);
echo $list;
