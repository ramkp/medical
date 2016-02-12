<?php

require_once './classes/Upload.php';
$upload=new Upload();
$file=$_FILES;
$list=$upload->upload_users_file($file);
echo $list;
