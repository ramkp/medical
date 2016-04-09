<?php

require_once './classes/Register.php';
$register=new Register();
$list=$register->get_policy_dialog();
echo $list;