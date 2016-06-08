<?php

require_once './classes/Partial.php';
$partial = new Partial();
$item = $_POST['item'];
$list = $partial->search_item(trim($item));
echo $list;


