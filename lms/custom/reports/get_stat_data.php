<?php

require_once './classes/Report.php';
$r = new Report();
$item = $_POST['stat'];
$list = $r->get_stat_data(json_decode($item));
echo $list;
