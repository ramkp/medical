<?php

require_once './classes/Hotel.php';
$h = new Hotel();
$hotel = $_POST['hotel'];
$h->add_hotel(json_decode($hotel));

