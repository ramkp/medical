<?php

require_once './classes/Register.php';
$rg=new Register();
$list=$rg->get_register_page();
echo $list;
