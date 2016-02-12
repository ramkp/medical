<?php

require_once './classes/Payment.php';
$signup = new Payment();
$group_common_section = $_POST['group_common_section'];
$users = $_POST['users'];
$tot_participants = $_POST['tot_participants'];
$list = $signup->get_group_payment_section(json_decode($group_common_section), json_decode($users), $tot_participants);
echo $list;
