<?php

require_once './classes/Register.php';
$reqister=new Register();
$categoryname=$_POST['category_name'];
$statename=$_POST['state_name'];
$list=$reqister->get_category_state_items($categoryname, $statename);
echo $list;
