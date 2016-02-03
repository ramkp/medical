<?php

require_once './classes/Register.php';
$cat_id=$_POST['cat_id'];
$pr=new Register();
$list=$pr->get_courses_by_category($cat_id);
echo $list;

