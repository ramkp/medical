<?php

require_once './classes/Register.php';
$r = new Register();
$list = $r->get_register_form();
echo $list;

