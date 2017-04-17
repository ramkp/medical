<?php

require_once './classes/Payments.php';
$p = new Payments(0);
$list = $p->verify_payments();
echo $list;
