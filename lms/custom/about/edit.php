<?php

require_once './classes/About.php';
$about=new About();
$data=$_POST['data'];
$list=$about->save_page_changes($data);
echo $list;