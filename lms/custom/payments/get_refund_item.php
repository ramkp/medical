<?php

require_once './classes/Payments.php';
$payments = new Payments(0);
$page = $_POST['id'];
$list = $payments->get_refund_item($page);
echo $list;

