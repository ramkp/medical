<?php

require_once './classes/Signup.php';
$signup=new Signup();
$user=$_POST['user'];
$list=$signup->get_payment_section_group(json_decode($user));
echo $list;