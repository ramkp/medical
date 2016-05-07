<?php

require_once './classes/Gallery.php';
$gl=new Gallery();
$files=$_FILES;
$post=$_POST;

/*
echo "<br>-------------------------<br>";
print_r($_FILES);
echo "<br>-------------------------<br>";

echo "<br>-------------------------<br>";
print_r($_POST);
echo "<br>-------------------------<br>";
*/

if (count($files)>0)  {
    $gl->upload_gallery_images($files,$post);
}

