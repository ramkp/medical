<?php

require_once './classes/navClass.php';
$nav=new navClass();
$list=$nav->renew_certificate();
echo $list;

