<?php

require_once './classes/Programs.php';
$pr=new Programs();
$cat_name=$_POST['cat_name'];
$list=$pr->get_category_items($cat_name);
echo $list;