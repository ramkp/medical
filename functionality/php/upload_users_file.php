<?php

require_once './classes/Upload.php';
$upload=new Upload();
$file=$_FILES;
$upload->upload_users_file($file);
