<?php

require_once './classes/Inventory.php';
$in = new Inventory();
$item = $_POST['hotel'];
$list = $in->search_hotel_item(json_decode($item));
echo $list;
