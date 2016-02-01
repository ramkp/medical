<?php

require_once './classes/Map.php';
$category_id=$_POST['category_id'];
$map=new Map($category_id);
$list=$map->refresh_google_map();
echo $list;
