<?php

require_once './classes/Late.php';
$late=new Late();
$list=$late->get_edit_page();
echo $list;
