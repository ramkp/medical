<?php

require_once './classes/Promotion.php';
$pr = new Promotion();
$users = $_POST['users'];
$list = $pr->get_add_new_campaigner_dialog(json_decode($users));
echo $list;
