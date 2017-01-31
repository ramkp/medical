<?php

require_once './classes/Instructors.php';
$in = new Instructors();
$item = $_POST['item'];
$list = $in->search_item(json_decode($item));
echo $list;
