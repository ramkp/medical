<?php

require_once './classes/Demographic.php';
$dm = new Demographic();
$criteria = $_POST['criteria'];
$list = $dm->get_demo_data(json_decode($criteria));
echo $list;
