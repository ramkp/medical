<?php

require_once './classes/Price.php';
$course_id = $_POST['course_id'];
$course_cost = $_POST['course_cost'];
$course_discount = $_POST['course_discount'];
$course_group_discount = $_POST['course_group_discount'];
$pr = new Price();
$list = $pr->update_item_price($course_id, $course_cost, $course_discount, $course_group_discount);
echo $list;
?>