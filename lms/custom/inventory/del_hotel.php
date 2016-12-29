<?php

require_once './classes/Inventory.php';
$in = new Inventory();
$id = $_POST['id'];
$in->delete_hotel($id);
