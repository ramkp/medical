<?php

require_once './classes/Upload.php';
$upload=new Upload();
$list=$upload->get_users_upload_form();
echo $list;