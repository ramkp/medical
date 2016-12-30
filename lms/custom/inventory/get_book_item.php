<?php

require_once './classes/Inventory.php';
$in = new Inventory();
$page = $_POST['id'];
$list = $in->get_book_item($page);
echo $list;
