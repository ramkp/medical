<?php

require_once './classes/Promotion.php';
$pm = new Promotion();
$data = $_POST['data'];
$enrolled_users = $_POST['enrolled_users'];
$workshop_users = $_POST['workshop_users'];
$list = $pm->add_new_campaign($data, $enrolled_users, $workshop_users);
echo $list;

