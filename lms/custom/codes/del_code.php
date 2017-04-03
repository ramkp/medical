<?php

require_once './classes/Codes.php';
$c = new Codes();
$id = $_POST['id'];
$list = $c->del_code($id);
echo $list;
