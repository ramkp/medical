<?php

require_once './classes/Codes.php';
$c = new Codes();
$name = $_POST['name'];
$list = $c->get_program_id($name);
echo $list;
