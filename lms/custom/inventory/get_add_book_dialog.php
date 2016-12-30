<?php

require_once './classes/Inventory.php';
$in = new Inventory();
$list = $in->get_add_book_dialog();
echo $list;
