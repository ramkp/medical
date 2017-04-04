<?php

require_once './classes/Codes.php';
$c = new Codes();
$userslist = $_POST['userslist'];
$program = $_POST['program'];
$list = $c->get_add_campaign_dialog($userslist, $program);
echo $list;

