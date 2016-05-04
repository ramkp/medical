<?php

require_once './classes/Login.php';
$login=new Login();
//print_r($_POST);
//echo "<br>";
$u=$_POST['login'];
$p=$_POST['password'];
$list=$login->verify_user($u, $p);
echo $list;