<?php

require_once './classes/Price.php';
$course_id = $_POST['course_id'];
$course_cost = $_POST['course_cost'];
$course_discount = $_POST['course_discount'];
$course_group_discount = $_POST['course_group_discount'];
$installment=$_POST['installment'];
$num_payments=$_POST['num_payments'];    
$post_states=$_POST['states'];
$states=json_decode($post_states)[0];

/*
echo "<br/>--------------<br/>";
print_r($_POST);
echo "<br/>--------------<br/>";
print_r($states);
echo "<br/>--------------<br/>";
*/

$pr = new Price();
$list = $pr->update_item_price($course_id,$course_cost,$course_discount,$course_group_discount,$installment,$num_payments,$states);
echo $list;
?>