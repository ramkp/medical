<?php

require_once './classes/Inventory.php';
$in = new Inventory();
$book = $_POST['book'];
$in->add_book(json_decode($book));
