<?php

require_once './classes/Hotel.php';
$h = new Hotel();
$id = $_POST['id'];
$list = $h->get_edit_hotel_dialog($id);
echo $list;

