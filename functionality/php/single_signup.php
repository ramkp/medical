<?php

require_once './classes/Payment.php';
$signup=new Payment();
$user=$_POST['user'];

/*
echo "<pre>";
print_r(json_decode($user));
echo "<pre>";
*/
$list=$signup->enroll_user(json_decode($user));
echo $list;