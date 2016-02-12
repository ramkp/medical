<?php

require_once './classes/Payment.php';
$signup=new Payment();
$group_name=$_POST['group_name'];
$list=$signup->is_group_exist($group_name);
echo $list;