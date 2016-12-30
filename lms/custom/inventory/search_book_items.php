<?php

require_once './classes/Inventory.php';
$in = new Inventory();
$book = $_POST['book'];
$list = $in->search_book_item(json_decode($book));
echo $list;
