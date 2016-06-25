<?php

require_once './classes/Index.php';
$index = new Index();
$id = $_POST['id'];
$title = $_POST['title'];
$slogan1 = $_POST['slogan1'];
$slogan2 = $_POST['slogan2'];
$slogan3 = $_POST['slogan3'];
$list = $index->update_slide($id, $title, $slogan1, $slogan2, $slogan3);
echo $list;
