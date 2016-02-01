<?php

require_once './classes/Gallery.php';
$gl=new Gallery();
$files=$_FILES;
if (count($files)>0)  {
    $gl->upload_gallery_images($files);
}

