<?php

require_once './classes/User.php';
$user = new User();
$page = $_POST['id'];
$email = $_POST['email'];
$list = $user->get_search_user_item($page, $email);
echo $list;

