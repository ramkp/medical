<?php

require_once './classes/Inventory.php';
$in = new Inventory();
$hotel = $_POST['hotel'];
$in->add_hotel(json_decode($hotel));
