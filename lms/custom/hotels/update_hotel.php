<?php

require_once './classes/Hotel.php';
$h = new Hotel();
$hotel = $_POST['hotel'];
$h->update_hotel(json_decode($hotel));
