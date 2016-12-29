<?php

require_once './classes/Inventory.php';
$in = new Inventory();
$list = $in->get_add_hotel_dialog();
echo $list;
