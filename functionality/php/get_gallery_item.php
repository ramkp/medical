<?php

require_once './classes/Gallery.php';
$gl = new Gallery();
$image = $_POST['image'];
$width=$_POST['width'];
$list = $gl->show_image($image, $width);
echo $list;

