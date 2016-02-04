<?php

require_once './classes/Register.php';
$rg=new Register();
$email=$_POST['email'];
$list=$rg->is_email_exists($email);
echo $list;