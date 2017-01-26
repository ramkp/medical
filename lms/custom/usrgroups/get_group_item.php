<?php

require_once './classes/Groups.php';
$g = new Groups();
$page = $_POST['id'];
$list = $g->get_group_item($page);
echo $list;

