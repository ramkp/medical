<?php

require_once './classes/Hotel.php';
$h = new Hotel();
$list = $h->get_add_hotel_dialog();
echo $list;

