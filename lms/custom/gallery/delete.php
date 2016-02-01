<?php

require_once './classes/Gallery.php';
$gl=new Gallery();
$items=$_POST['items'];
$list=$gl->delete_gallery_thumbs($items);

