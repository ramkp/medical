<?php

require_once './classes/Index.php';
$index = new Index();
$id = $_POST['id'];
$list = $index->get_slide_detailes($id);
echo $list;
