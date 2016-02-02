<?php

require_once './classes/Programs.php';
$pr=new Programs();
$cat_name=$_POST['cat_name'];
$list=$pr->get_school_page($cat_name);
echo $list;