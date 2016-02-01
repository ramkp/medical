<?php

require_once './classes/Map.php';
$category_id=$_POST['category_id'];
$map=new Map($category_id);
$list=$map->get_index_page();
echo $list;
