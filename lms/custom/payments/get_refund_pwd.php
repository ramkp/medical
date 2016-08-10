<?php

require_once './classes/Payments.php';
$payments = new Payments(0);
$list = $payments->get_update_refund_pwd_page();
echo $list;
