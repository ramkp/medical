<?php

require_once './classes/Payment.php';
$signup=new Payment();
$user=$_POST['user'];
$list=$signup->enroll_user(json_decode($user));
echo $list;