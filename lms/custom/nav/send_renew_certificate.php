<?php

require_once './classes/navClass.php';
$nav=new navClass();
$list=$nav->send_invoice_for_renew();
echo $list;
