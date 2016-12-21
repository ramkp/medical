<?php

require_once './classes/Promotion.php';
$pr = new Promotion();
$users = $_POST['users'];
$text = $_POST['text'];
$list = $pr->add_new_campaign2($text, $users);
echo $list;
