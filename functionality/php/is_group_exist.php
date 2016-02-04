<?php

require_once './classes/Signup.php';
$signup=new Signup();
$group_name=$_POST['group_name'];
$list=$signup->is_group_exist($group_name);
echo $list;