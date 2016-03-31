<?php

require_once './classes/About.php';
$about=new About();
$list=$about->get_edit_page();
echo $list;
