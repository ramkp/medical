<?php

require_once './classes/Codes.php';
$c = new Codes();
$page = $_POST['id'];
$list = $c->get_code_item($page);
echo $list;
