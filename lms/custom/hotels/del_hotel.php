<?php

require_once './classes/Hotel.php';
$h = new Hotel();
$id = $_POST['id'];
$list = $h->del_hotel($id);
echo $list;
