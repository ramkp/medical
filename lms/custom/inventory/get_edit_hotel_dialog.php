<?php

require_once './classes/Inventory.php';
$in = new Inventory();
$id = $_POST['id'];
$list = $in->get_edit_hotel_dialog($id);
echo $list;
