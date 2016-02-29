<?php

require_once './classes/Price.php';
$id=$_POST['id'];
$pr=new Price();
$list=$pr->get_items_from_category($id);
echo $list;
