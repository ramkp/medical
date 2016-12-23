<?php

require_once './classes/Hotel.php';
$h = new Hotel();
$state = $_POST['state'];
$city = $_POST['city'];
$list = $h->search_hotel($state, $city);
echo $list;
