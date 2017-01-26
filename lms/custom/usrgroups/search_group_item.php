<?php

require_once './classes/Groups.php';
$g = new Groups();
$item = $_POST['item'];
$list = $g->search_group_item($item);
echo $list;
