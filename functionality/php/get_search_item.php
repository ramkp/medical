<?php

require_once './classes/Search.php';
$search=new Search();
$item=$_POST['search_item'];
$list=$search->get_search_item($item);
echo $list;

