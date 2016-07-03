<?php

require_once './classes/Promotion.php';
$pm = new Promotion();
$id = $_POST['id'];
$list = $pm->get_campaign_stat($id);
echo $list;
