<?php

require_once './classes/User.php';
$user = new User();
$email = $_POST['email'];
$list = $user->search_user_by_email($email);
echo $list;

?>

