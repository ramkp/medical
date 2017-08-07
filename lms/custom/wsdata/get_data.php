<?php

require_once './classes/Wsdata.php';
$ws = new Wsdata();
$dates = $_POST['dates'];
$list = $ws->get_workshop_data(json_decode($dates));
echo $list;
