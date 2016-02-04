<?php

require_once './classes/Signup.php';
$signup=new Signup();
$user=$_POST['user'];
$list=$signup->enroll_user(json_decode($user));
echo $list;