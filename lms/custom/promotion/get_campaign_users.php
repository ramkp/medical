<?php

require_once './classes/Promotion.php';
$pr = new Promotion();
$user_criteria = $_POST['user_criteria'];
$list = $pr->get_campaign_users(json_decode($user_criteria));
echo $list;
