<?php

require_once './classes/navClass.php';
$nav=new navClass();
$list=$nav->get_certificate();
echo $list;
