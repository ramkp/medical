<?php

require_once './classes/User.php';
$user = new User();
$page = $_POST['id'];
$list = $user->get_user_item($page);
echo $list;
