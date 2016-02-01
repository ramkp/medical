<?php

require_once './classes/Gallery.php';
$gl=new Gallery();
$list=$gl->refresh_gallery_thumbs();
echo $list;