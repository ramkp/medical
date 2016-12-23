<?php

require_once './classes/Hotel.php';
$h = new Hotel();
$page = $_POST['id'];
$list = $h->get_hotel_item($page);
echo $list;
